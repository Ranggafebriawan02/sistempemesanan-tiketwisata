<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Pemesanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $stats = match (true) {
            $user->isAdmin() => [
                'Paket Wisata' => Paket::count(),
                'Total Pemesanan' => Pemesanan::count(),
                'Total Konsumen' => User::where('level', User::ROLE_KONSUMEN)->count(),
                'Pembayaran Valid' => 'Rp '.number_format((float) Transaksi::where('status', Transaksi::STATUS_VALID)->sum('jumlah_bayar'), 0, ',', '.'),
            ],
            $user->isBendahara() => [
                'Menunggu Validasi' => Transaksi::where('status', Transaksi::STATUS_MENUNGGU)->count(),
                'Pembayaran Valid' => Transaksi::where('status', Transaksi::STATUS_VALID)->count(),
                'Pembayaran Ditolak' => Transaksi::where('status', Transaksi::STATUS_DITOLAK)->count(),
                'Total Diterima' => 'Rp '.number_format((float) Transaksi::where('status', Transaksi::STATUS_VALID)->sum('jumlah_bayar'), 0, ',', '.'),
            ],
            $user->isDirektur() => [
                'Total Pemesanan' => Pemesanan::count(),
                'Pembayaran Valid' => Transaksi::where('status', Transaksi::STATUS_VALID)->count(),
                'Pembayaran Ditolak' => Transaksi::where('status', Transaksi::STATUS_DITOLAK)->count(),
                'Total Pendapatan' => 'Rp '.number_format((float) Transaksi::where('status', Transaksi::STATUS_VALID)->sum('jumlah_bayar'), 0, ',', '.'),
            ],
            default => [
                'Pemesanan Saya' => Pemesanan::where('user_id', $user->id)->count(),
                'Menunggu Validasi' => Transaksi::where('user_id', $user->id)->where('status', Transaksi::STATUS_MENUNGGU)->count(),
                'Pembayaran Valid' => Transaksi::where('user_id', $user->id)->where('status', Transaksi::STATUS_VALID)->count(),
                'Total Transaksi' => 'Rp '.number_format((float) Transaksi::where('user_id', $user->id)->where('status', Transaksi::STATUS_VALID)->sum('jumlah_bayar'), 0, ',', '.'),
            ],
        };

        return view('dashboard', compact('user', 'stats'));
    }
}
