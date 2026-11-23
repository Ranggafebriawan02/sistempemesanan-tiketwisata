@extends('layouts.app')

@section('title', 'Katalog Paket Wisata — PT. Puma Star Jaya')

@push('styles')
<style>
    /* Scandinavian Clean Catalog */
    .page-hero {
        background: #ffffff;
        border-bottom: 1px solid #ebebeb;
        padding: 44px 0 32px;
        text-align: left;
    }
    .page-hero .eyebrow {
        font-family: 'Outfit', sans-serif;
        color: #ff385c;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        font-size: 12px;
    }
    .page-hero h1 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(28px, 3.6vw, 42px);
        letter-spacing: -.025em;
        color: #222222;
        margin-top: 6px;
        font-weight: 800;
    }
    .page-hero p {
        margin-top: 8px;
        max-width: 680px;
        color: #717171;
        font-size: 15px;
        line-height: 1.6;
    }

    .catalog-wrapper {
        padding: 40px 0 70px;
    }

    .paket-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
    }
    .paket-card {
        background: #ffffff;
        border: 1px solid #ebebeb;
        border-radius: 18px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        box-shadow: var(--shadow-subtle);
    }
    .paket-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-card);
        border-color: #dddddd;
    }
    .paket-media {
        height: 190px;
        position: relative;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 14px 16px;
        color: #fff;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    .paket-media::after {
        content: "";
        position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(0, 0, 0, .25) 0%, transparent 40%, rgba(0, 0, 0, .6) 100%);
    }
    .paket-media .dest {
        position: relative; z-index: 2;
        font-size: 11px; font-weight: 800;
        background: rgba(255, 255, 255, 0.95);
        color: #222222;
        padding: 4px 10px; border-radius: var(--radius-full);
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .paket-media .rating-badge {
        position: relative; z-index: 2;
        font-size: 11px; font-weight: 800;
        background: #222222;
        color: #ffffff;
        padding: 4px 10px;
        border-radius: var(--radius-full);
    }

    .paket-body { padding: 22px; display: flex; flex-direction: column; flex: 1; }
    .paket-body h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 17px;
        font-weight: 800;
        line-height: 1.35;
        color: #222222;
        letter-spacing: -.01em;
    }
    .paket-body p { margin-top: 8px; font-size: 13px; color: #717171; flex: 1; line-height: 1.55; }
    
    /* Clean Neutral Chips (No blue/cyan) */
    .paket-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-top: 14px; }
    .chip {
        font-size: 11.5px; font-weight: 600;
        background: #f7f7f7;
        color: #484848;
        border: 1px solid #e2e2e2;
        padding: 4px 10px; border-radius: var(--radius-full);
    }
    .chip-bus {
        background: #fff8f6;
        color: #c13515;
        border-color: #ffd8d0;
        font-weight: 700;
    }

    .paket-foot {
        display: flex; align-items: flex-end; justify-content: space-between;
        gap: 12px; margin-top: 18px; padding-top: 16px;
        border-top: 1px solid #f0f0f0;
    }
    .price-block { display: flex; flex-direction: column; }
    .price-label { font-size: 10px; color: #717171; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; }
    .price { font-family: 'Outfit', sans-serif; font-size: 19px; font-weight: 800; color: #222222; letter-spacing: -.02em; }
    .price small { font-size: 12px; color: #717171; font-weight: 500; font-family: 'Plus Jakarta Sans', sans-serif; }

    /* Button Lihat Detail: Jelas, Tegas, Senada Airbnb Primary */
    .btn-detail {
        background: #ff385c;
        color: #ffffff !important;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        padding: 9px 18px;
        border-radius: var(--radius-full);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(255, 56, 92, 0.28);
        transition: all .18s ease;
        white-space: nowrap;
    }
    .btn-detail:hover {
        background: #e00b41;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(255, 56, 92, 0.4);
    }

    .empty-state {
        background: #fff; border: 1px dashed #dddddd; border-radius: 18px;
        padding: 56px; text-align: center; color: #717171;
    }

    @media (max-width: 980px) { .paket-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .paket-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">KATALOG PERJALANAN</span>
            <h1>Pilihan Paket Wisata</h1>
            <p>Pilih perjalanan yang sesuai untuk rombongan sekolah, instansi dinas, jamaah ziarah, atau liburan keluarga Anda dengan armada bus pariwisata prima.</p>
        </div>
    </section>

    <section class="container catalog-wrapper">
        @if ($paket->isEmpty())
            <div class="empty-state">
                <p>Belum ada paket wisata yang tersedia saat ini.</p>
            </div>
        @else
            <div class="paket-grid">
                @foreach ($paket as $item)
                    @php
                        $mediaStyle = $item->foto_url
                            ? "background-image: url('".$item->foto_url."');"
                            : "background: #222222;";
                    @endphp
                    <article class="paket-card">
                        <div class="paket-media" style="{{ $mediaStyle }}">
                            <span class="dest">{{ $item->destinasi }}</span>
                            <span class="rating-badge">★ 4.94</span>
                        </div>
                        <div class="paket-body">
                            <h3>{{ $item->judul }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($item->deskripsi, 110) }}</p>
                            
                            <div class="paket-meta">
                                @if ($item->bus)
                                    <span class="chip chip-bus">🚌 {{ $item->bus }}</span>
                                @endif
                                <span class="chip">✨ {{ $item->fasilitas ? \Illuminate\Support\Str::limit($item->fasilitas, 32) : 'Fasilitas lengkap' }}</span>
                            </div>

                            <div class="paket-foot">
                                <div class="price-block">
                                    <span class="price-label">Mulai Dari</span>
                                    <div class="price">
                                        Rp {{ number_format((float) $item->harga, 0, ',', '.') }}
                                        <small>/pax</small>
                                    </div>
                                </div>
                                <a href="{{ route('paket.show', $item) }}" class="btn-detail">
                                    Lihat Detail
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
