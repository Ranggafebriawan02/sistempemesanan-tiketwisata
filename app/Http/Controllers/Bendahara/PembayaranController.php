<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    private const VALID_STATUSES = [
        'belum_bayar',
        Transaksi::STATUS_MENUNGGU,
        Transaksi::STATUS_VALID,
        Transaksi::STATUS_DITOLAK,
    ];

    public function index(Request $request): View
    {
        $status = (string) $request->query('status', 'semua');
        $q = trim((string) $request->query('q', ''));

        if (! in_array($status, self::VALID_STATUSES, true)) {
            $status = 'semua';
        }

        $pemesanan = Pemesanan::query()
            ->with(['user', 'paket', 'transaksiTerakhir'])
            ->when($q !== '', function (Builder $query) use ($q) {
                $query->where(function (Builder $sub) use ($q) {
                    $sub->where('kode_pemesanan', 'like', "%{$q}%")
                        ->orWhereHas('user', fn (Builder $user) => $user->where('nama', 'like', "%{$q}%"))
                        ->orWhereHas('paket', fn (Builder $paket) => $paket->where('judul', 'like', "%{$q}%"));
                });
            });

        $this->applyStatusFilter($pemesanan, $status);

        $pemesanan = $pemesanan->latest()->paginate(10)->withQueryString();

        $counts = [
            'semua' => Pemesanan::count(),
            'belum_bayar' => $this->applyStatusFilter(Pemesanan::query(), 'belum_bayar')->count(),
            'menunggu' => $this->applyStatusFilter(Pemesanan::query(), Transaksi::STATUS_MENUNGGU)->count(),
            'valid' => $this->applyStatusFilter(Pemesanan::query(), Transaksi::STATUS_VALID)->count(),
            'ditolak' => $this->applyStatusFilter(Pemesanan::query(), Transaksi::STATUS_DITOLAK)->count(),
        ];

        return view('bendahara.pemesanan.index', compact('pemesanan', 'status', 'q', 'counts'));
    }

    public function show(Pemesanan $pemesanan): View
    {
        $pemesanan->load(['user', 'paket', 'kupon', 'transaksi' => fn ($query) => $query->latest()]);

        return view('bendahara.pemesanan.show', compact('pemesanan'));
    }

    public function validasi(Request $request, Transaksi $transaksi): RedirectResponse
    {
        $data = $request->validate([
            'keputusan' => ['required', 'in:valid,ditolak'],
            'catatan' => ['nullable', 'string', 'max:500', 'required_if:keputusan,ditolak'],
        ], [
            'keputusan.required' => 'Pilih keputusan validasi.',
            'keputusan.in' => 'Keputusan validasi tidak valid.',
            'catatan.required_if' => 'Alasan penolakan wajib diisi.',
            'catatan.max' => 'Catatan maksimal 500 karakter.',
        ]);

        if ($transaksi->status !== Transaksi::STATUS_MENUNGGU) {
            return back()->with('error', 'Transaksi ini sudah divalidasi sebelumnya.');
        }

        $transaksi->update([
            'status' => $data['keputusan'],
            'catatan' => $data['catatan'] ?? null,
        ]);

        $pesan = $data['keputusan'] === Transaksi::STATUS_VALID
            ? 'Pembayaran berhasil divalidasi. Kuitansi sudah dapat dicetak.'
            : 'Pembayaran ditolak. Konsumen dapat mengunggah ulang bukti pembayaran.';

        return redirect()
            ->route('bendahara.pemesanan.show', $transaksi->pemesanan_id)
            ->with($data['keputusan'] === Transaksi::STATUS_VALID ? 'success' : 'info', $pesan);
    }

    public function kuitansi(Transaksi $transaksi): View|RedirectResponse
    {
        if ($transaksi->status !== Transaksi::STATUS_VALID) {
            return redirect()
                ->route('bendahara.pemesanan.show', $transaksi->pemesanan_id)
                ->with('error', 'Kuitansi hanya dapat dicetak untuk pembayaran yang sudah valid.');
        }

        $transaksi->load(['pemesanan.user', 'pemesanan.paket']);

        $terbilang = trim(preg_replace('/\s+/', ' ', $this->terbilang((int) $transaksi->jumlah_bayar))).' rupiah';

        return view('bendahara.kuitansi', compact('transaksi', 'terbilang'));
    }

    private function applyStatusFilter(Builder $query, string $status): Builder
    {
        if (in_array($status, self::VALID_STATUSES, true) && $status !== 'semua') {
            return $query->whereStatusPembayaran($status);
        }

        return $query;
    }

    /**
     * Ubah nominal angka menjadi kalimat terbilang (Bahasa Indonesia).
     */
    private function terbilang(int $nilai): string
    {
        $nilai = abs($nilai);
        $huruf = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];

        if ($nilai < 12) {
            return $huruf[$nilai];
        }

        if ($nilai < 20) {
            return $this->terbilang($nilai - 10).' belas';
        }

        if ($nilai < 100) {
            return $this->terbilang(intdiv($nilai, 10)).' puluh '.$this->terbilang($nilai % 10);
        }

        if ($nilai < 200) {
            return 'seratus '.$this->terbilang($nilai - 100);
        }

        if ($nilai < 1000) {
            return $this->terbilang(intdiv($nilai, 100)).' ratus '.$this->terbilang($nilai % 100);
        }

        if ($nilai < 2000) {
            return 'seribu '.$this->terbilang($nilai - 1000);
        }

        if ($nilai < 1_000_000) {
            return $this->terbilang(intdiv($nilai, 1000)).' ribu '.$this->terbilang($nilai % 1000);
        }

        if ($nilai < 1_000_000_000) {
            return $this->terbilang(intdiv($nilai, 1_000_000)).' juta '.$this->terbilang($nilai % 1_000_000);
        }

        if ($nilai < 1_000_000_000_000) {
            return $this->terbilang(intdiv($nilai, 1_000_000_000)).' miliar '.$this->terbilang($nilai % 1_000_000_000);
        }

        return $this->terbilang(intdiv($nilai, 1_000_000_000_000)).' triliun '.$this->terbilang($nilai % 1_000_000_000_000);
    }
}
