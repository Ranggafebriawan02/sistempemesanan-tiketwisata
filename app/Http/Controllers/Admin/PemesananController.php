<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemesananController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'semua');
        $q = trim((string) $request->query('q', ''));

        $query = Pemesanan::with(['paket', 'user', 'transaksiTerakhir']);

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('kode_pemesanan', 'like', "%{$q}%")
                    ->orWhere('nama_rombongan', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($u) => $u->where('nama', 'like', "%{$q}%")->orWhere('username', 'like', "%{$q}%"))
                    ->orWhereHas('paket', fn ($p) => $p->where('judul', 'like', "%{$q}%"));
            });
        }

        if ($status === 'belum_bayar') {
            $query->whereDoesntHave('transaksi');
        } elseif (in_array($status, ['menunggu', 'valid', 'ditolak'])) {
            $query->whereHas('transaksiTerakhir', fn ($t) => $t->where('status', $status));
        }

        $pemesanan = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'semua' => Pemesanan::count(),
            'menunggu' => Pemesanan::whereHas('transaksiTerakhir', fn ($t) => $t->where('status', 'menunggu'))->count(),
            'valid' => Pemesanan::whereHas('transaksiTerakhir', fn ($t) => $t->where('status', 'valid'))->count(),
            'ditolak' => Pemesanan::whereHas('transaksiTerakhir', fn ($t) => $t->where('status', 'ditolak'))->count(),
            'belum_bayar' => Pemesanan::whereDoesntHave('transaksi')->count(),
        ];

        return view('admin.pemesanan.index', compact('pemesanan', 'status', 'q', 'counts'));
    }

    public function show(Pemesanan $pemesanan): View
    {
        $pemesanan->load(['paket', 'user', 'kupon', 'transaksi.user']);

        return view('admin.pemesanan.show', compact('pemesanan'));
    }
}

