<?php

namespace App\Services;

use App\Models\Pemesanan;
use App\Models\Transaksi;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
        
        // Atasi kendala sertifikat SSL cURL lokal di Windows
        Config::$curlOptions = [
            CURLOPT_HTTPHEADER => [],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
        ];
    }

    public function getSnapToken(Pemesanan $pemesanan): string
    {
        if ($pemesanan->snap_token) {
            return $pemesanan->snap_token;
        }

        $params = [
            'transaction_details' => [
                'order_id' => $pemesanan->kode_pemesanan . '-' . time(),
                'gross_amount' => (int) round($pemesanan->harga),
            ],
            'customer_details' => [
                'first_name' => $pemesanan->nama_rombongan ?: $pemesanan->user->nama,
                'email' => $pemesanan->user->email,
                'phone' => $pemesanan->no_hp,
            ],
            'item_details' => [
                [
                    'id' => 'PKT-' . $pemesanan->paket_id,
                    'price' => (int) round($pemesanan->harga / max(1, $pemesanan->jumlah_pax)),
                    'quantity' => (int) $pemesanan->jumlah_pax,
                    'name' => mb_strimwidth($pemesanan->paket->judul, 0, 45, '...'),
                ]
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        $pemesanan->update(['snap_token' => $snapToken]);

        return $snapToken;
    }

    /**
     * Cek status transaksi langsung ke server Midtrans (termasuk simulator)
     * dan otomatis sinkronkan ke basis data lokal (tabel Transaksi).
     */
    public function syncStatusFromMidtrans(Pemesanan $pemesanan): ?Transaksi
    {
        if (empty(Config::$serverKey)) {
            return null;
        }

        // Coba cek dengan kode pemesanan secara langsung atau order id yang cocok
        $possibleOrderIds = [];

        // Ambil order_id dari catatan transaksi terakhir jika ada
        $lastTrx = $pemesanan->transaksi()->latest()->first();
        if ($lastTrx && preg_match('/Order ID:\s*([A-Za-z0-9\-_]+)/', $lastTrx->catatan ?? '', $matches)) {
            $possibleOrderIds[] = $matches[1];
        }

        $possibleOrderIds[] = $pemesanan->kode_pemesanan;

        $statusData = null;
        foreach ($possibleOrderIds as $orderId) {
            try {
                $res = Transaction::status($orderId);
                if ($res && (isset($res->transaction_status) || is_array($res))) {
                    $statusData = (object) $res;
                    break;
                }
            } catch (\Exception $e) {
                // Lanjut ke percobaan berikutnya jika order_id tidak ditemukan
            }
        }

        if (! $statusData) {
            return null;
        }

        $transactionStatus = $statusData->transaction_status ?? null;
        $paymentType = $statusData->payment_type ?? 'midtrans';
        $fraudStatus = $statusData->fraud_status ?? null;
        $grossAmount = isset($statusData->gross_amount) ? (float) $statusData->gross_amount : (float) $pemesanan->harga;
        $orderId = $statusData->order_id ?? $pemesanan->kode_pemesanan;

        $status = Transaksi::STATUS_MENUNGGU;
        if ($transactionStatus === 'capture') {
            $status = ($fraudStatus === 'challenge') ? Transaksi::STATUS_MENUNGGU : Transaksi::STATUS_VALID;
        } elseif ($transactionStatus === 'settlement') {
            $status = Transaksi::STATUS_VALID;
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $status = Transaksi::STATUS_DITOLAK;
        } elseif ($transactionStatus === 'pending') {
            $status = Transaksi::STATUS_MENUNGGU;
        }

        $metode = mb_strimwidth('midtrans (' . $paymentType . ')', 0, 30, '');

        $transaksi = Transaksi::updateOrCreate(
            [
                'pemesanan_id' => $pemesanan->id,
                'alat_pembayaran' => $metode,
            ],
            [
                'user_id' => $pemesanan->user_id,
                'status' => $status,
                'jumlah_bayar' => $grossAmount,
                'tanggal_bayar' => now(),
                'catatan' => 'Pembayaran via Midtrans (' . $paymentType . ' - ' . ($transactionStatus ?? 'sukses') . ') [Order ID: ' . $orderId . ']',
            ]
        );

        return $transaksi;
    }
}


