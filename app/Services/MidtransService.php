<?php

namespace App\Services;

use App\Models\Pemesanan;
use Midtrans\Config;
use Midtrans\Snap;

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
}

