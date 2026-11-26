<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Transaksi;
use App\Services\MidtransService;
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

    public function index(Request $request): View|\Illuminate\Http\JsonResponse
    {
        $status = (string) $request->query('status', 'semua');
        $periode = (string) $request->query('periode', 'semua');
        $dari = (string) $request->query('dari', '');
        $sampai = (string) $request->query('sampai', '');
        $bulan = (string) $request->query('bulan', '');
        $tahun = (string) $request->query('tahun', '');
        $q = trim((string) $request->query('q', ''));

        if (! in_array($status, self::VALID_STATUSES, true)) {
            $status = 'semua';
        }

        $baseQuery = Pemesanan::query()
            ->with(['user', 'paket', 'transaksiTerakhir']);

        // Filter status
        $this->applyStatusFilter($baseQuery, $status);

        // Filter periode
        $this->applyPeriodeFilter($baseQuery, $periode, $dari, $sampai, $bulan, $tahun);

        // Pencarian q
        if ($q !== '') {
            $baseQuery->where(function (Builder $sub) use ($q) {
                $sub->where('kode_pemesanan', 'like', "%{$q}%")
                    ->orWhereHas('user', fn (Builder $user) => $user->where('nama', 'like', "%{$q}%"))
                    ->orWhereHas('paket', fn (Builder $paket) => $paket->where('judul', 'like', "%{$q}%"));
            });
        }

        $pemesanan = $baseQuery->latest()->paginate(10)->withQueryString();

        // Hitung counts per status dengan mempertimbangkan filter periode yang aktif
        $countQuery = function (string $st) use ($periode, $dari, $sampai, $bulan, $tahun) {
            $q = Pemesanan::query();
            $this->applyStatusFilter($q, $st);
            $this->applyPeriodeFilter($q, $periode, $dari, $sampai, $bulan, $tahun);
            return $q->count();
        };

        $counts = [
            'semua' => $countQuery('semua'),
            'belum_bayar' => $countQuery('belum_bayar'),
            'menunggu' => $countQuery(Transaksi::STATUS_MENUNGGU),
            'valid' => $countQuery(Transaksi::STATUS_VALID),
            'ditolak' => $countQuery(Transaksi::STATUS_DITOLAK),
        ];

        // Daftar tahun yang ada pemesanannya untuk dropdown
        $daftarTahun = Pemesanan::selectRaw('YEAR(created_at) as thn')
            ->distinct()
            ->orderByDesc('thn')
            ->pluck('thn')
            ->toArray();
        if (empty($daftarTahun)) {
            $daftarTahun = [(int) date('Y')];
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html_table' => view('bendahara.pemesanan.partials.table', compact('pemesanan'))->render(),
                'html_pagination' => $pemesanan->links('pagination.simple')->render(),
                'total' => $pemesanan->total(),
                'counts' => $counts,
            ]);
        }

        return view('bendahara.pemesanan.index', compact(
            'pemesanan', 'status', 'periode', 'dari', 'sampai', 'bulan', 'tahun', 'q', 'counts', 'daftarTahun'
        ));
    }

    private function applyPeriodeFilter(Builder $query, string $periode, string $dari, string $sampai, string $bulan, string $tahun): Builder
    {
        switch ($periode) {
            case 'hari_ini':
                $query->whereDate('created_at', now()->toDateString());
                break;
            case 'seminggu':
                $query->whereDate('created_at', '>=', now()->subDays(7)->toDateString())
                      ->whereDate('created_at', '<=', now()->toDateString());
                break;
            case 'bulan':
                $bln = (int) ($bulan ?: date('m'));
                $thn = (int) ($tahun ?: date('Y'));
                $query->whereMonth('created_at', $bln)->whereYear('created_at', $thn);
                break;
            case 'tahun':
                $thn = (int) ($tahun ?: date('Y'));
                $query->whereYear('created_at', $thn);
                break;
            case 'kustom':
                if ($dari !== '') {
                    $query->whereDate('created_at', '>=', $dari);
                }
                if ($sampai !== '') {
                    $query->whereDate('created_at', '<=', $sampai);
                }
                break;
        }

        return $query;
    }

    public function show(Pemesanan $pemesanan, MidtransService $midtrans): View
    {
        // Cek pembaruan status transaksi otomatis dari Midtrans jika ada snap_token dan belum valid
        if ($pemesanan->statusPembayaran() !== Transaksi::STATUS_VALID && $pemesanan->snap_token) {
            try {
                $midtrans->syncStatusFromMidtrans($pemesanan);
            } catch (\Exception $e) {
                \Log::info('Bendahara show sync Midtrans notice: ' . $e->getMessage());
            }
        }

        $pemesanan->load(['user', 'paket', 'kupon', 'transaksi' => fn ($query) => $query->latest()]);

        return view('bendahara.pemesanan.show', compact('pemesanan'));
    }

    public function syncMidtrans(Pemesanan $pemesanan, MidtransService $midtrans): RedirectResponse
    {
        $transaksi = null;
        try {
            $transaksi = $midtrans->syncStatusFromMidtrans($pemesanan);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memeriksa status Midtrans: ' . $e->getMessage());
        }

        if ($transaksi) {
            return back()->with('success', 'Status transaksi berhasil disinkronkan dari Midtrans: ' . $transaksi->status);
        }

        return back()->with('info', 'Belum ada transaksi pembayaran tercatat di server Midtrans untuk pesanan ini.');
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
