@extends('layouts.app')

@section('title', 'Pesanan Saya — PT. Puma Star Jaya')

@push('styles')
<style>
    .orders-page { padding: 34px 0 64px; }
    .page-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 22px; }
    .page-head h1 { font-size: clamp(22px, 3vw, 30px); letter-spacing: -.025em; }
    .page-head p { margin-top: 5px; font-size: 13.5px; color: var(--slate-500); }

    .orders-grid { display: grid; gap: 14px; }
    .order-card {
        background: #fff; border: 1px solid var(--slate-200); border-radius: 14px;
        padding: 18px 20px; display: grid; grid-template-columns: 1.5fr 1fr auto; gap: 18px;
        align-items: center; transition: border-color .18s, box-shadow .18s;
    }
    .order-card:hover { border-color: #99f6e4; box-shadow: var(--shadow-sm); }
    .order-title { font-size: 15px; font-weight: 800; letter-spacing: -.01em; }
    .order-meta { display: flex; flex-wrap: wrap; gap: 6px 16px; margin-top: 6px; font-size: 12px; color: var(--slate-500); }
    .order-meta strong { color: var(--slate-700); font-weight: 700; }
    .order-code { font-size: 11px; font-weight: 700; color: var(--teal-700); letter-spacing: .04em; margin-bottom: 3px; }
    .order-side { font-size: 12.5px; color: var(--slate-500); display: grid; gap: 5px; }
    .order-side strong { color: var(--slate-900); }
    .order-total { font-size: 16px; font-weight: 800; color: var(--teal-800); white-space: nowrap; }
    .order-actions { display: flex; gap: 8px; align-items: center; justify-content: flex-end; }

    .empty-state {
        background: #fff; border: 1px dashed var(--slate-300); border-radius: 16px;
        padding: 54px 24px; text-align: center;
    }
    .empty-state h2 { font-size: 18px; }
    .empty-state p { margin: 8px auto 20px; max-width: 420px; font-size: 13.5px; color: var(--slate-500); }

    .pager { display: flex; justify-content: center; align-items: center; gap: 14px; margin-top: 26px; font-size: 12.5px; color: var(--slate-500); }
    .pager a { text-decoration: none; font-weight: 700; color: var(--teal-700); border: 1px solid var(--slate-300); border-radius: 8px; padding: 7px 12px; background: #fff; }
    .pager a:hover { background: var(--teal-50); }

    @media (max-width: 820px) {
        .order-card { grid-template-columns: 1fr; gap: 14px; }
        .order-actions { justify-content: flex-start; flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')
    <div class="container orders-page">
        <div class="page-head">
            <div>
                <h1>Pesanan Saya</h1>
                <p>Pantau status pemesanan dan pembayaran paket wisata Anda.</p>
            </div>
            <a href="{{ route('paket.index') }}" class="btn btn-primary btn-sm">+ Pesan Paket Baru</a>
        </div>

        @if ($pemesanan->isEmpty())
            <div class="empty-state">
                <h2>Belum ada pesanan</h2>
                <p>Anda belum pernah memesan paket wisata. Pilih paket yang menarik lalu klik <strong>Pesan Sekarang</strong>.</p>
                <a href="{{ route('paket.index') }}" class="btn btn-primary">Lihat Paket Wisata</a>
            </div>
        @else
            <div class="orders-grid">
                @foreach ($pemesanan as $item)
                    @php $status = $item->statusPembayaran(); @endphp
                    <article class="order-card">
                        <div>
                            <div class="order-code">{{ $item->kode_pemesanan }}</div>
                            <div class="order-title">{{ $item->paket->judul ?? 'Paket tidak tersedia' }}</div>
                            <div class="order-meta">
                                <span>Berangkat: <strong>{{ $item->tanggal_berangkat->translatedFormat('d M Y') }}</strong></span>
                                <span>Peserta: <strong>{{ $item->jumlah_pax }} orang</strong></span>
                                @if ($item->nama_rombongan)
                                    <span>Rombongan: <strong>{{ $item->nama_rombongan }}</strong></span>
                                @endif
                            </div>
                        </div>

                        <div class="order-side">
                            <span class="status status-{{ $status }}">{{ $item->labelStatus() }}</span>
                            <span>Total tagihan <strong>{{ 'Rp '.number_format((float) $item->harga, 0, ',', '.') }}</strong></span>
                            <span>Dibuat {{ $item->created_at->translatedFormat('d M Y H:i') }}</span>
                        </div>

                        <div class="order-actions">
                            <a href="{{ route('konsumen.pemesanan.show', $item) }}" class="btn btn-outline btn-sm">Detail</a>
                            @if ($item->bisaBayar())
                                <a href="{{ route('konsumen.pemesanan.bayar', $item) }}" class="btn btn-primary btn-sm">Bayar</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($pemesanan->hasPages())
                <div class="pager">
                    @if ($pemesanan->onFirstPage())
                        <span>&laquo; Sebelumnya</span>
                    @else
                        <a href="{{ $pemesanan->previousPageUrl() }}" rel="prev">&laquo; Sebelumnya</a>
                    @endif
                    <span>Halaman {{ $pemesanan->currentPage() }} dari {{ $pemesanan->lastPage() }}</span>
                    @if ($pemesanan->hasMorePages())
                        <a href="{{ $pemesanan->nextPageUrl() }}" rel="next">Berikutnya &raquo;</a>
                    @else
                        <span>Berikutnya &raquo;</span>
                    @endif
                </div>
            @endif
        @endif
    </div>
@endsection
