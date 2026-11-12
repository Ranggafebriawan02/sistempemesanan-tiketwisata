@extends('layouts.app')

@section('title', 'Paket Wisata — PT. Puma Star Jaya')

@push('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, #09332f 0%, #0d544c 50%, #0f766e 100%);
        color: #ffffff;
        padding: 56px 0;
        position: relative;
        box-shadow: inset 0 -1px 0 rgba(255, 255, 255, .1);
    }
    .page-hero .eyebrow {
        color: #fde68a !important;
        font-weight: 800;
        letter-spacing: .12em;
    }
    .page-hero .eyebrow::before {
        background: #f59e0b;
    }
    .page-hero h1 {
        font-size: clamp(26px, 3.4vw, 38px);
        letter-spacing: -.025em;
        color: #ffffff !important;
        margin-top: 6px;
        font-weight: 800;
        text-shadow: 0 2px 4px rgba(0, 0, 0, .25);
    }
    .page-hero p {
        margin-top: 10px;
        max-width: 660px;
        color: #e0f2fe !important;
        font-size: 14.5px;
        line-height: 1.6;
    }

    .paket-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
    .paket-card {
        background: #fff; border: 1px solid var(--slate-200); border-radius: 16px;
        overflow: hidden; display: flex; flex-direction: column;
        transition: transform .2s, box-shadow .2s, border-color .2s;
    }
    .paket-card:hover { transform: translateY(-4px); box-shadow: var(--shadow); border-color: #99f6e4; }
    .paket-media { height: 132px; position: relative; display: flex; align-items: flex-end; padding: 14px 16px; color: #fff; }
    .paket-media::after { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, transparent 20%, rgba(8, 33, 31, .55)); }
    .paket-media .dest {
        position: relative; z-index: 1; font-size: 12px; font-weight: 700;
        background: rgba(255, 255, 255, .18); border: 1px solid rgba(255, 255, 255, .3);
        padding: 4px 10px; border-radius: 999px; backdrop-filter: blur(6px);
    }
    .paket-body { padding: 18px; display: flex; flex-direction: column; flex: 1; }
    .paket-body h3 { font-size: 16px; line-height: 1.35; }
    .paket-body p { margin-top: 8px; font-size: 12.5px; color: var(--slate-500); flex: 1; }
    .paket-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-top: 12px; }
    .chip {
        font-size: 11px; font-weight: 700; background: var(--teal-50);
        color: var(--teal-700); border: 1px solid #99f6e4;
        padding: 3px 9px; border-radius: 999px;
    }
    .paket-foot {
        display: flex; align-items: center; justify-content: space-between;
        gap: 10px; margin-top: 16px; padding-top: 14px; border-top: 1px dashed var(--slate-200);
    }
    .price { font-size: 16px; font-weight: 800; color: var(--teal-800); letter-spacing: -.02em; }
    .price small { display: block; font-size: 10.5px; color: var(--slate-500); font-weight: 600; letter-spacing: 0; }

    .empty-state {
        background: #fff; border: 1px dashed var(--slate-300); border-radius: 16px;
        padding: 48px; text-align: center; color: var(--slate-500);
    }

    @media (max-width: 980px) { .paket-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .paket-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">Katalog</span>
            <h1>Paket Wisata PT. Puma Star Jaya</h1>
            <p>Pilih paket yang sesuai kebutuhan perjalanan Anda. Semua paket sudah mencakup transportasi, penginapan, konsumsi, pemandu, dan tiket objek wisata sesuai keterangan fasilitas.</p>
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
                                'linear-gradient(135deg, #0f766e, #134e4a)',
                                'linear-gradient(135deg, #b45309, #78350f)',
                                'linear-gradient(135deg, #1d4ed8, #1e3a8a)',
                                'linear-gradient(135deg, #be185d, #831843)',
                                'linear-gradient(135deg, #047857, #064e3b)',
                                'linear-gradient(135deg, #7c3aed, #4c1d95)',
                            ];
                            $mediaStyle = $item->foto
                                ? "background-image: linear-gradient(180deg, rgba(8,33,31,.15), rgba(8,33,31,.7)), url('".asset('storage/'.$item->foto)."'); background-size: cover; background-position: center;"
                                : 'background: '.$gradients[$loop->index % count($gradients)].';';
                        @endphp
                        <article class="paket-card">
                            <div class="paket-media" style="{{ $mediaStyle }}">
                                <span class="dest">{{ $item->destinasi }}</span>
                            </div>
                            <div class="paket-body">
                                <h3>{{ $item->judul }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($item->deskripsi, 110) }}</p>
                                <div class="paket-meta">
                                    @if ($item->bus)
                                        <span class="chip">{{ $item->bus }}</span>
                                    @endif
                                    <span class="chip">{{ $item->fasilitas ? \Illuminate\Support\Str::limit($item->fasilitas, 34) : 'Fasilitas lengkap' }}</span>
                                </div>
                                <div class="paket-foot">
                                    <div class="price">
                                        Rp {{ number_format((float) $item->harga, 0, ',', '.') }}
                                        <small>per orang</small>
                                    </div>
                                    <a href="{{ route('paket.show', $item) }}" class="btn btn-primary btn-sm">Detail</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
