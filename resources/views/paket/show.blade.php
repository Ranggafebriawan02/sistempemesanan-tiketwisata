@extends('layouts.app')

@section('title', $paket->judul.' — PT. Puma Star Jaya')

@push('styles')
<style>
    .breadcrumb { padding: 18px 0 0; font-size: 13px; color: #687176; font-weight: 600; }
    .breadcrumb a { color: #0194f3; text-decoration: none; font-weight: 700; }
    .breadcrumb a:hover { text-decoration: underline; }

    .detail-grid { display: grid; grid-template-columns: 1fr 380px; gap: 32px; align-items: start; margin-top: 20px; }
    .detail-main {
        background: #fff; border: 1px solid #e5e9ed; border-radius: 16px; overflow: hidden;
        box-shadow: 0 2px 12px rgba(3, 18, 38, 0.04);
    }
    .detail-banner {
        height: 280px; position: relative; display: flex; align-items: flex-end; padding: 28px;
        color: #fff;
    }
    .detail-banner::after { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(3, 18, 38, .15) 0%, rgba(3, 18, 38, .85) 100%); }
    .detail-banner .inner { position: relative; z-index: 1; }
    .detail-banner .dest {
        display: inline-block; font-size: 11.5px; font-weight: 800;
        background: rgba(3, 18, 38, 0.7); backdrop-filter: blur(6px);
        border: 1px solid rgba(255, 255, 255, .3);
        padding: 4px 12px; border-radius: 6px; margin-bottom: 10px; text-transform: uppercase;
    }
    .detail-banner h1 { font-size: clamp(24px, 3vw, 36px); letter-spacing: -.025em; color: #fff; line-height: 1.2; font-weight: 800; }

    .detail-content { padding: 32px; }
    .detail-content h2 {
        font-size: 17px; font-weight: 800; color: #1c2430; margin-bottom: 12px;
        display: flex; align-items: center; gap: 8px;
    }
    .detail-content h2 svg { color: #0194f3; width: 20px; height: 20px; }
    .detail-content p { font-size: 14.5px; color: #434d5b; line-height: 1.7; }
    .detail-content hr { border: 0; border-top: 1px dashed #e5e9ed; margin: 26px 0; }

    .fasilitas-list { list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .fasilitas-list li {
        display: flex; gap: 12px; align-items: center;
        font-size: 13.5px; font-weight: 600; color: #1c2430;
        background: #f7f9fa; border: 1px solid #e5e9ed;
        padding: 12px 16px; border-radius: 10px;
    }
    .fasilitas-list .tick {
        flex-shrink: 0; width: 22px; height: 22px; border-radius: 50%;
        background: #e7f5ff; color: #0194f3;
        display: grid; place-items: center;
        font-size: 12px; font-weight: 900;
    }

    .detail-side { position: sticky; top: 110px; }
    .booking-card {
        background: #fff; border: 1px solid #e5e9ed; border-radius: 16px;
        padding: 26px; box-shadow: 0 8px 24px rgba(3, 18, 38, 0.08);
    }
    .booking-card .label { font-size: 11.5px; font-weight: 700; color: #687176; text-transform: uppercase; }
    .booking-card .big-price { font-size: 32px; font-weight: 900; color: #ff5e1f; letter-spacing: -.03em; margin-top: 2px; }
    .booking-card .big-price small { font-size: 12.5px; color: #687176; font-weight: 600; }
    .booking-card .meta { display: grid; gap: 12px; margin: 20px 0; padding: 18px 0; border-top: 1px dashed #e5e9ed; border-bottom: 1px dashed #e5e9ed; }
    .booking-card .meta div { display: flex; justify-content: space-between; gap: 12px; font-size: 13.5px; }
    .booking-card .meta span:first-child { color: #687176; font-weight: 500; }
    .booking-card .meta span:last-child { font-weight: 800; color: #1c2430; text-align: right; }
    .booking-card .note { font-size: 12px; color: #687176; margin-top: 14px; line-height: 1.6; }

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
                        ? "background-image: linear-gradient(180deg, rgba(8,33,31,.1), rgba(8,33,31,.75)), url('".$paket->foto_url."'); background-size: cover; background-position: center;"
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
