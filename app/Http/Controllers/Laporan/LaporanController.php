<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use App\Models\Pemesanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LaporanController extends Controller
{
    private const STATUS_PEMESANAN = ['semua', 'belum_bayar', 'menunggu', 'valid', 'ditolak'];

    private const STATUS_PEMBAYARAN = ['semua', 'menunggu', 'valid', 'ditolak'];

    public function pemesanan(Request $request): View
    {
        $filter = $this->filterPemesanan($request);
        $query = $this->queryPemesanan($filter);

        return view('laporan.pemesanan', [
            'pemesanan' => $query->paginate(20)->withQueryString(),
            'filter' => $filter,
            'ringkasan' => $this->ringkasanPemesanan($query),
            'daftarPaket' => Paket::orderBy('judul')->get(['id', 'judul']),
            'periode' => $this->periodeLabel($filter),
        ]);
    }

    public function cetakPemesanan(Request $request): View
    {
        $filter = $this->filterPemesanan($request);
        $query = $this->queryPemesanan($filter);

        return view('laporan.cetak-pemesanan', [
            'pemesanan' => $query->get(),
            'filter' => $filter,
            'ringkasan' => $this->ringkasanPemesanan($query),
            'periode' => $this->periodeLabel($filter),
            'namaPaket' => $filter['paket_id']
                ? Paket::whereKey($filter['paket_id'])->value('judul')
                : null,
            'penandatangan' => $this->penandatangan(),
            'dicetak' => now(),
        ]);
    }

    public function pembayaran(Request $request): View
    {
        $filter = $this->filterPembayaran($request);
        $query = $this->queryPembayaran($filter);

        return view('laporan.pembayaran', [
            'transaksi' => $query->paginate(20)->withQueryString(),
            'filter' => $filter,
            'ringkasan' => $this->ringkasanPembayaran($query),
            'periode' => $this->periodeLabel($filter),
        ]);
    }

    public function cetakPembayaran(Request $request): View
    {
        $filter = $this->filterPembayaran($request);
        $query = $this->queryPembayaran($filter);

        return view('laporan.cetak-pembayaran', [
            'transaksi' => $query->get(),
            'filter' => $filter,
            'ringkasan' => $this->ringkasanPembayaran($query),
            'periode' => $this->periodeLabel($filter),
            'penandatangan' => $this->penandatangan(),
            'dicetak' => now(),
        ]);
    }

    /**
     * @return array{dari: string, sampai: string, status: string, paket_id: int|null}
     */
    private function filterPemesanan(Request $request): array
    {
        $request->merge([
            'dari' => $request->input('dari') ?: now()->startOfMonth()->toDateString(),
            'sampai' => $request->input('sampai') ?: now()->toDateString(),
            'status' => $request->input('status') ?: 'semua',
        ]);

        $data = $request->validate([
            'dari' => ['required', 'date'],
            'sampai' => ['required', 'date', 'after_or_equal:dari'],
            'status' => ['required', 'in:'.implode(',', self::STATUS_PEMESANAN)],
            'paket_id' => ['nullable', 'integer', 'exists:paket,id'],
        ], [
            'dari.required' => 'Tanggal awal periode wajib diisi.',
            'dari.date' => 'Tanggal awal periode tidak valid.',
            'sampai.required' => 'Tanggal akhir periode wajib diisi.',
            'sampai.date' => 'Tanggal akhir periode tidak valid.',
            'sampai.after_or_equal' => 'Tanggal akhir tidak boleh lebih awal dari tanggal awal.',
            'status.in' => 'Status pemesanan tidak valid.',
            'paket_id.exists' => 'Paket wisata yang dipilih tidak ditemukan.',
        ]);

        return [
            'dari' => $data['dari'],
            'sampai' => $data['sampai'],
            'status' => $data['status'],
            'paket_id' => isset($data['paket_id']) && $data['paket_id'] !== null
                ? (int) $data['paket_id']
                : null,
        ];
    }

    /**
     * @return array{dari: string, sampai: string, status: string, paket_id: null}
     */
    private function filterPembayaran(Request $request): array
    {
        $request->merge([
            'dari' => $request->input('dari') ?: now()->startOfMonth()->toDateString(),
            'sampai' => $request->input('sampai') ?: now()->toDateString(),
            'status' => $request->input('status') ?: 'semua',
        ]);

        $data = $request->validate([
            'dari' => ['required', 'date'],
            'sampai' => ['required', 'date', 'after_or_equal:dari'],
            'status' => ['required', 'in:'.implode(',', self::STATUS_PEMBAYARAN)],
        ], [
            'dari.required' => 'Tanggal awal periode wajib diisi.',
            'dari.date' => 'Tanggal awal periode tidak valid.',
            'sampai.required' => 'Tanggal akhir periode wajib diisi.',
            'sampai.date' => 'Tanggal akhir periode tidak valid.',
            'sampai.after_or_equal' => 'Tanggal akhir tidak boleh lebih awal dari tanggal awal.',
            'status.in' => 'Status pembayaran tidak valid.',
        ]);

        return [
            'dari' => $data['dari'],
            'sampai' => $data['sampai'],
            'status' => $data['status'],
            'paket_id' => null,
        ];
    }

    /**
     * @param  array{dari: string, sampai: string, status: string, paket_id: int|null}  $filter
     */
    private function queryPemesanan(array $filter): Builder
    {
        return Pemesanan::query()
            ->with(['user', 'paket', 'transaksiTerakhir'])
            ->whereDate('created_at', '>=', $filter['dari'])
            ->whereDate('created_at', '<=', $filter['sampai'])
            ->when(
                $filter['status'] !== 'semua',
                fn (Builder $query) => $query->whereStatusPembayaran($filter['status'])
            )
            ->when(
                $filter['paket_id'],
                fn (Builder $query, int $paketId) => $query->where('paket_id', $paketId)
            )
            ->latest();
    }

    /**
     * @param  array{dari: string, sampai: string, status: string, paket_id: int|null}  $filter
     */
    private function queryPembayaran(array $filter): Builder
    {
        return Transaksi::query()
            ->with(['pemesanan.user', 'pemesanan.paket'])
            ->whereNotNull('tanggal_bayar')
            ->whereDate('tanggal_bayar', '>=', $filter['dari'])
            ->whereDate('tanggal_bayar', '<=', $filter['sampai'])
            ->when(
                $filter['status'] !== 'semua',
                fn (Builder $query) => $query->where('status', $filter['status'])
            )
            ->orderByDesc('tanggal_bayar')
            ->orderByDesc('id');
    }

    /**
     * @return array{jumlah: int, pax: int, nilai: float, lunas_jumlah: int, lunas_nilai: float}
     */
    private function ringkasanPemesanan(Builder $query): array
    {
        return [
            'jumlah' => (clone $query)->reorder()->count(),
            'pax' => (int) (clone $query)->reorder()->sum('jumlah_pax'),
            'nilai' => (float) (clone $query)->reorder()->sum('harga'),
            'lunas_jumlah' => (clone $query)->reorder()->whereStatusPembayaran(Transaksi::STATUS_VALID)->count(),
            'lunas_nilai' => (float) (clone $query)->reorder()->whereStatusPembayaran(Transaksi::STATUS_VALID)->sum('harga'),
        ];
    }

    /**
     * @return array{jumlah: int, total: float, valid: float, menunggu: float, ditolak: float, valid_jumlah: int}
     */
    private function ringkasanPembayaran(Builder $query): array
    {
        return [
            'jumlah' => (clone $query)->reorder()->count(),
            'total' => (float) (clone $query)->reorder()->sum('jumlah_bayar'),
            'valid' => (float) (clone $query)->reorder()->where('status', Transaksi::STATUS_VALID)->sum('jumlah_bayar'),
            'valid_jumlah' => (clone $query)->reorder()->where('status', Transaksi::STATUS_VALID)->count(),
            'menunggu' => (float) (clone $query)->reorder()->where('status', Transaksi::STATUS_MENUNGGU)->sum('jumlah_bayar'),
            'ditolak' => (float) (clone $query)->reorder()->where('status', Transaksi::STATUS_DITOLAK)->sum('jumlah_bayar'),
        ];
    }

    /**
     * @param  array{dari: string, sampai: string, status: string, paket_id: int|null}  $filter
     * @return array{dari: string, sampai: string}
     */
    private function periodeLabel(array $filter): array
    {
        return [
            'dari' => Carbon::parse($filter['dari'])->translatedFormat('d F Y'),
            'sampai' => Carbon::parse($filter['sampai'])->translatedFormat('d F Y'),
        ];
    }

    /**
     * @return array{bendahara: string, direktur: string}
     */
    private function penandatangan(): array
    {
        return [
            'bendahara' => User::where('level', User::ROLE_BENDAHARA)->orderBy('id')->value('nama') ?? 'Bendahara',
            'direktur' => User::where('level', User::ROLE_DIREKTUR)->orderBy('id')->value('nama') ?? 'Direktur',
        ];
    }
}
