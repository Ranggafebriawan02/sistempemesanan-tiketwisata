@extends('layouts.app')

@section('title', $paket->judul.' — PT. Puma Star Jaya')

@push('styles')
<style>
    .breadcrumb { padding: 22px 0 0; font-size: 12.5px; color: var(--slate-500); }
    .breadcrumb a { color: var(--teal-700); text-decoration: none; font-weight: 600; }
    .breadcrumb a:hover { text-decoration: underline; }

    .detail-grid { display: grid; grid-template-columns: 1fr 360px; gap: 32px; align-items: start; }
    .detail-main {
        background: #fff; border: 1px solid var(--slate-200); border-radius: 18px; overflow: hidden;
    }
    .detail-banner {
        height: 240px; position: relative; display: flex; align-items: flex-end; padding: 24px;
        color: #fff;
    }
    .detail-banner::after { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, transparent 20%, rgba(8, 33, 31, .65)); }
    .detail-banner .inner { position: relative; z-index: 1; }
    .detail-banner .dest {
        display: inline-block; font-size: 12px; font-weight: 700;
        background: rgba(255, 255, 255, .18); border: 1px solid rgba(255, 255, 255, .3);
        padding: 4px 10px; border-radius: 999px; margin-bottom: 8px;
    }
    .detail-banner h1 { font-size: clamp(24px, 3vw, 34px); letter-spacing: -.025em; color: #fff; line-height: 1.2; }

    .detail-content { padding: 28px; }
    .detail-content h2 { font-size: 15px; text-transform: uppercase; letter-spacing: .08em; color: var(--teal-700); margin-bottom: 10px; }
    .detail-content p { font-size: 14px; color: var(--slate-700); }
    .detail-content hr { border: 0; border-top: 1px dashed var(--slate-200); margin: 24px 0; }

    .fasilitas-list { list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .fasilitas-list li {
        display: flex; gap: 10px; align-items: flex-start;
        font-size: 13.5px; color: var(--slate-700);
        background: var(--slate-50); border: 1px solid var(--slate-200);
        padding: 10px 12px; border-radius: 10px;
    }
    .fasilitas-list .tick {
        flex-shrink: 0; width: 20px; height: 20px; border-radius: 50%;
        background: var(--teal-50); color: var(--teal-700);
        border: 1px solid #99f6e4; display: grid; place-items: center;
        font-size: 10px; font-weight: 800;
    }

    .detail-side { position: sticky; top: 92px; }
    .booking-card {
        background: #fff; border: 1px solid var(--slate-200); border-radius: 18px;
        padding: 24px; box-shadow: var(--shadow);
    }
    .booking-card .label { font-size: 12px; font-weight: 600; color: var(--slate-500); }
    .booking-card .big-price { font-size: 30px; font-weight: 800; color: var(--teal-800); letter-spacing: -.03em; margin-top: 2px; }
    .booking-card .big-price small { font-size: 11.5px; color: var(--slate-500); font-weight: 600; letter-spacing: 0; }
    .booking-card .meta { display: grid; gap: 10px; margin: 18px 0; padding: 16px 0; border-top: 1px dashed var(--slate-200); border-bottom: 1px dashed var(--slate-200); }
    .booking-card .meta div { display: flex; justify-content: space-between; gap: 12px; font-size: 13px; }
    .booking-card .meta span:first-child { color: var(--slate-500); }
    .booking-card .meta span:last-child { font-weight: 700; color: var(--slate-900); text-align: right; }
    .booking-card .note { font-size: 11.5px; color: var(--slate-500); margin-top: 12px; line-height: 1.6; }

    @media (max-width: 980px) {
        .detail-grid { grid-template-columns: 1fr; }
        .detail-side { position: static; }
        .fasilitas-list { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
    <div class="container breadcrumb">
        <a href="{{ route('home') }}">Home</a> &nbsp;/&nbsp;
        <a href="{{ route('paket.index') }}">Paket Wisata</a> &nbsp;/&nbsp;
        <span>{{ $paket->judul }}</span>
    </div>

    <section class="page-section" style="padding-top:22px;">
        <div class="container detail-grid">
            <div class="detail-main">
                @php
                    $gradients = [
                        'linear-gradient(135deg, #0f766e, #134e4a)',
                        'linear-gradient(135deg, #b45309, #78350f)',
                        'linear-gradient(135deg, #1d4ed8, #1e3a8a)',
                        'linear-gradient(135deg, #be185d, #831843)',
                        'linear-gradient(135deg, #047857, #064e3b)',
                        'linear-gradient(135deg, #7c3aed, #4c1d95)',
                    ];
                    $bannerStyle = $paket->foto
                        ? "background-image: linear-gradient(180deg, rgba(8,33,31,.1), rgba(8,33,31,.75)), url('".asset('storage/'.$paket->foto)."'); background-size: cover; background-position: center;"
                        : 'background: '.$gradients[$paket->id % count($gradients)].';';
                @endphp
                <div class="detail-banner" style="{{ $bannerStyle }}">
                    <div class="inner">
                        <span class="dest">{{ $paket->destinasi }}</span>
                        <h1>{{ $paket->judul }}</h1>
                    </div>
                </div>

                <div class="detail-content">
                    <h2>Deskripsi Paket</h2>
                    <p>{{ $paket->deskripsi }}</p>

                    <hr>

                    <h2>Fasilitas</h2>
                    <ul class="fasilitas-list">
                        @foreach (array_filter(array_map('trim', explode(',', $paket->fasilitas))) as $fasilitas)
                            <li><span class="tick">&#10003;</span><span>{{ $fasilitas }}</span></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <aside class="detail-side">
                <div class="booking-card">
                    <span class="label">Harga paket</span>
                    <div class="big-price">
                        Rp {{ number_format((float) $paket->harga, 0, ',', '.') }}
                        <small>/ orang</small>
                    </div>

                    <div class="meta">
                        <div><span>Destinasi</span><span>{{ $paket->destinasi }}</span></div>
                        <div><span>Transportasi</span><span>{{ $paket->bus ?: 'Menyesuaikan' }}</span></div>
                        <div><span>Status</span><span>Tersedia</span></div>
                    </div>

                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary btn-block">Masuk untuk Memesan</a>
                        <p class="note">Belum punya akun? <a href="{{ route('register') }}" style="color:var(--teal-700);font-weight:700;">Daftar gratis</a> terlebih dahulu.</p>
                    @else
                        @if (auth()->user()->isKonsumen())
                            <a href="{{ route('konsumen.pemesanan.create', $paket) }}" class="btn btn-primary btn-block">Pesan Sekarang</a>
                            <p class="note">Isi data perjalanan, lalu unggah bukti pembayaran pada langkah berikutnya.</p>
                        @else
                            <p class="note">Anda masuk sebagai <strong>{{ auth()->user()->level }}</strong>. Pemesanan hanya dapat dilakukan oleh akun konsumen.</p>
                        @endif
                    @endguest
                </div>
            </aside>
        </div>
    </section>
@endsection
