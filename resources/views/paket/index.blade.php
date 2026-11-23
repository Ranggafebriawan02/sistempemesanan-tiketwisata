@extends('layouts.app')

@section('title', 'Paket Wisata — PT. Puma Star Jaya')

@push('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, #0194f3 0%, #007ce8 60%, #0264b1 100%);
        color: #ffffff;
        padding: 50px 0;
        position: relative;
    }
    .page-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, .15) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: .35;
        pointer-events: none;
    }
    .page-hero .container { position: relative; z-index: 1; }
    .page-hero .eyebrow {
        color: #ffeb3b !important;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
        font-size: 12px;
    }
    .page-hero h1 {
        font-size: clamp(26px, 3.4vw, 38px);
        letter-spacing: -.025em;
        color: #ffffff !important;
        margin-top: 6px;
        font-weight: 800;
    }
    .page-hero p {
        margin-top: 10px;
        max-width: 660px;
        color: #e0f2fe !important;
        font-size: 14.5px;
        line-height: 1.6;
    }

    .paket-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
    .paket-card {
        background: #fff;
        border: 1px solid #e5e9ed;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        box-shadow: 0 2px 8px rgba(3, 18, 38, 0.04);
    }
    .paket-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(3, 18, 38, 0.12);
        border-color: #b9e3ff;
    }
    .paket-media {
        height: 154px;
        position: relative;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 12px 14px;
        color: #fff;
    }
    .paket-media::after {
        content: "";
        position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(3, 18, 38, .3) 0%, transparent 40%, rgba(3, 18, 38, .75) 100%);
    }
    .paket-media .dest {
        position: relative; z-index: 1;
        font-size: 11.5px; font-weight: 700;
        background: rgba(3, 18, 38, 0.7);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, .25);
        padding: 4px 10px; border-radius: 6px;
        color: #fff;
    }
    .paket-media .rating-badge {
        position: relative; z-index: 1;
        font-size: 11px; font-weight: 800;
        background: #0194f3;
        color: #fff;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .paket-body { padding: 18px 20px; display: flex; flex-direction: column; flex: 1; }
    .paket-body h3 { font-size: 16px; font-weight: 800; line-height: 1.35; color: var(--slate-900); }
    .paket-body p { margin-top: 8px; font-size: 12.5px; color: var(--slate-500); flex: 1; line-height: 1.5; }
    .paket-meta { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-top: 12px; }
    .chip {
        font-size: 11px; font-weight: 700;
        background: #f0f7ff;
        color: #0194f3;
        border: 1px solid #cce5ff;
        padding: 3px 8px; border-radius: 6px;
    }
    .chip-bus {
        background: #fff6ed;
        color: #ea580c;
        border-color: #ffedd5;
    }
    .paket-foot {
        display: flex; align-items: flex-end; justify-content: space-between;
        gap: 10px; margin-top: 16px; padding-top: 14px;
        border-top: 1px dashed #e2e8f0;
    }
    .price-block { display: flex; flex-direction: column; }
    .price-label { font-size: 10.5px; color: var(--slate-500); font-weight: 600; text-transform: uppercase; }
    .price { font-size: 17px; font-weight: 900; color: #ff5e1f; letter-spacing: -.02em; }
    .price small { font-size: 11px; color: var(--slate-500); font-weight: 600; }

    .empty-state {
        background: #fff; border: 1px dashed var(--slate-300); border-radius: 14px;
        padding: 48px; text-align: center; color: var(--slate-500);
    }

    @media (max-width: 980px) { .paket-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .paket-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">KATALOG WISATA</span>
            <h1>Paket Wisata Pilihan</h1>
            <p>Pilih paket perjalanan yang sesuai kebutuhan liburan, study tour, atau ziarah Anda. Seluruh paket sudah termasuk transportasi nyaman, penginapan, makan, pemandu wisata, dan tiket masuk objek wisata.</p>
        </div>
    </section>

    <section class="page-section">
        <div class="container">
            @if ($paket->isEmpty())
                <div class="empty-state">
                    <p>Belum ada paket wisata yang tersedia saat ini.</p>
                </div>
            @else
                <div class="paket-grid">
                    @foreach ($paket as $item)
                        @php
                            $gradients = [
                                'linear-gradient(135deg, #0194f3, #0264b1)',
                                'linear-gradient(135deg, #007ce8, #0056a3)',
                                'linear-gradient(135deg, #0f4c46, #08211f)',
                                'linear-gradient(135deg, #1d4ed8, #1e3a8a)',
                                'linear-gradient(135deg, #0284c7, #0369a1)',
                                'linear-gradient(135deg, #0f766e, #134e4a)',
                            ];
                            $mediaStyle = $item->foto_url
                                ? "background-image: linear-gradient(180deg, rgba(3,18,38,.2), rgba(3,18,38,.75)), url('".$item->foto_url."'); background-size: cover; background-position: center;"
                                : 'background: '.$gradients[$loop->index % count($gradients)].';';
                        @endphp
                        <article class="paket-card">
                            <div class="paket-media" style="{{ $mediaStyle }}">
                                <span class="dest">{{ $item->destinasi }}</span>
                                <span class="rating-badge">★ 4.9</span>
                            </div>
                            <div class="paket-body">
                                <h3>{{ $item->judul }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($item->deskripsi, 110) }}</p>
                                <div class="paket-meta">
                                    @if ($item->bus)
                                        <span class="chip chip-bus">🚌 {{ $item->bus }}</span>
                                    @endif
                                    <span class="chip">✨ {{ $item->fasilitas ? \Illuminate\Support\Str::limit($item->fasilitas, 34) : 'Fasilitas lengkap' }}</span>
                                </div>
                                <div class="paket-foot">
                                    <div class="price-block">
                                        <span class="price-label">Mulai Dari</span>
                                        <div class="price">
                                            Rp {{ number_format((float) $item->harga, 0, ',', '.') }}
                                            <small>/pax</small>
                                        </div>
                                    </div>
                                    <a href="{{ route('paket.show', $item) }}" class="btn btn-amber btn-sm">Lihat Detail</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
