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

        $orderIdParts = explode('-', $notif->order_id);
        // Order id format: PSJ-YYYYMMDD-XXXX-<timestamp>
        // Prefix kode_pemesanan is 3 parts (PSJ, date, random string)
        $kodePemesanan = $orderIdParts[0] . '-' . ($orderIdParts[1] ?? '') . '-' . ($orderIdParts[2] ?? '');

        $pemesanan = Pemesanan::where('kode_pemesanan', $kodePemesanan)->first();

        if (! $pemesanan) {
            return response()->json(['message' => 'Order not found'], 404);
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

        Transaksi::updateOrCreate(
            [
                'pemesanan_id' => $pemesanan->id,
                'alat_pembayaran' => 'midtrans (' . $paymentType . ')',
            ],
            [
                'user_id' => $pemesanan->user_id,
                'status' => $status,
                'jumlah_bayar' => $grossAmount,
                'tanggal_bayar' => now(),
                'catatan' => 'Pembayaran otomatis via Midtrans (' . $paymentType . ' - ' . $transactionStatus . ')',
            ]
        );

        return response()->json(['message' => 'Webhook handled successfully']);
    }
}

