@extends('layouts.app')

@section('title', 'Pesanan Saya — PT. Puma Star Jaya')

@push('styles')
<style>
    .orders-page { padding: 36px 0 70px; }
    .page-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 24px; }
    .page-head h1 { font-family: 'Outfit', sans-serif; font-size: clamp(24px, 3vw, 32px); font-weight: 800; letter-spacing: -.025em; color: #222222; }
    .page-head p { margin-top: 5px; font-size: 14px; color: #717171; }

    .orders-grid { display: grid; gap: 16px; }
    .order-card {
        background: #fff; border: 1px solid #ebebeb; border-radius: 18px;
        padding: 22px 24px; display: grid; grid-template-columns: 1.5fr 1fr auto; gap: 20px;
        align-items: center; transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        box-shadow: var(--shadow-subtle);
    }
    .order-card:hover {
        transform: translateY(-2px);
        border-color: #dddddd;
        box-shadow: var(--shadow-card);
    }
    .order-title { font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; letter-spacing: -.01em; color: #222222; }
    .order-meta { display: flex; flex-wrap: wrap; gap: 6px 16px; margin-top: 8px; font-size: 12.5px; color: #717171; }
    .order-meta strong { color: #222222; font-weight: 700; }
    .order-code { font-size: 11px; font-weight: 800; color: #ff385c; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 4px; }
    .order-side { font-size: 13px; color: #717171; display: grid; gap: 6px; }
    .order-side strong { color: #222222; }
    .order-total { font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; color: #222222; white-space: nowrap; }
    .order-actions { display: flex; gap: 10px; align-items: center; justify-content: flex-end; }

    .empty-state {
        background: #fff; border: 1px dashed #dddddd; border-radius: 20px;
        padding: 60px 24px; text-align: center;
    }
    .empty-state h2 { font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 800; color: #222222; }
    .empty-state p { margin: 10px auto 24px; max-width: 440px; font-size: 14px; color: #717171; line-height: 1.6; }

    .pager { display: flex; justify-content: center; align-items: center; gap: 14px; margin-top: 28px; font-size: 13px; color: #717171; }
    .pager a { text-decoration: none; font-weight: 700; color: #222222; border: 1px solid #dddddd; border-radius: var(--radius-full); padding: 8px 16px; background: #fff; transition: all .15s; }
    .pager a:hover { background: #f7f7f7; border-color: #222222; }

    @media (max-width: 820px) {
        .order-card { grid-template-columns: 1fr; gap: 16px; }
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
