<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Transaksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');

        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid notification: ' . $e->getMessage()], 400);
        }

        $orderId = (string) $notif->order_id;
        
        // Cari pemesanan berdasarkan kode_pemesanan:
        // Format order_id bisa: PSJ-YYYYMMDD-XXXX-timestamp atau PMS-YYYYMMDD-XXXXX-timestamp atau langsung kode_pemesanan
        $pemesanan = Pemesanan::where('kode_pemesanan', $orderId)->first();

        if (! $pemesanan) {
            // Coba ambil prefix (misal PSJ-YYYYMMDD-XXXX atau PMS-YYYYMMDD-XXXXX)
            $parts = explode('-', $orderId);
            if (count($parts) >= 3) {
                $possibleKode = $parts[0] . '-' . $parts[1] . '-' . $parts[2];
                $pemesanan = Pemesanan::where('kode_pemesanan', $possibleKode)->first();
            }
        }

        if (! $pemesanan) {
            // Coba pencarian dengan LIKE prefix
            $pemesanan = Pemesanan::whereRaw('? LIKE CONCAT(kode_pemesanan, "%")', [$orderId])->first();
        }

        if (! $pemesanan) {
            return response()->json(['message' => 'Order not found for order_id: ' . $orderId], 404);
        }

        $transactionStatus = $notif->transaction_status;
        $paymentType = $notif->payment_type;
        $fraudStatus = $notif->fraud_status ?? null;
        $grossAmount = (float) $notif->gross_amount;

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $status = Transaksi::STATUS_MENUNGGU;
            } elseif ($fraudStatus == 'accept') {
                $status = Transaksi::STATUS_VALID;
            } else {
                $status = Transaksi::STATUS_MENUNGGU;
            }
        } elseif ($transactionStatus == 'settlement') {
            $status = Transaksi::STATUS_VALID;
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $status = Transaksi::STATUS_DITOLAK;
        } elseif ($transactionStatus == 'pending') {
            $status = Transaksi::STATUS_MENUNGGU;
        } else {
            $status = Transaksi::STATUS_MENUNGGU;
        }

        $metode = mb_strimwidth('midtrans (' . $paymentType . ')', 0, 30, '');

        Transaksi::updateOrCreate(
            [
                'pemesanan_id' => $pemesanan->id,
                'alat_pembayaran' => $metode,
            ],
            [
                'user_id' => $pemesanan->user_id,
                'status' => $status,
                'jumlah_bayar' => $grossAmount,
                'tanggal_bayar' => now(),
                'catatan' => 'Pembayaran otomatis via Midtrans (' . $paymentType . ' - ' . $transactionStatus . ') [Order ID: ' . $orderId . ']',
            ]
        );

        return response()->json(['message' => 'Webhook handled successfully']);
    }
}

