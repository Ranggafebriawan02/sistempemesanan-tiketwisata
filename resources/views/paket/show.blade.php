@extends('layouts.app')

@section('title', $paket->judul.' — PT. Puma Star Jaya')

@push('styles')
<style>
    .breadcrumb-clean {
        padding: 24px 0 0;
        font-size: 13.5px;
        color: #717171;
    }
    .breadcrumb-clean a {
        color: #222222;
        font-weight: 600;
        text-decoration: underline;
    }
    .breadcrumb-clean span {
        color: #717171;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 36px;
        align-items: start;
        margin-top: 24px;
        padding-bottom: 70px;
    }
    .detail-main {
        background: #ffffff;
        border: 1px solid #ebebeb;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--shadow-subtle);
    }
    .detail-photo {
        height: 340px;
        width: 100%;
        object-fit: cover;
        background: #ebebeb;
    }
    .detail-body {
        padding: 36px;
    }
    .dest-pill {
        display: inline-block;
        font-size: 11.5px;
        font-weight: 800;
        background: #f7f7f7;
        color: #222222;
        border: 1px solid #dddddd;
        padding: 4px 12px;
        border-radius: var(--radius-full);
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: 12px;
    }
    .detail-body h1 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(26px, 3.2vw, 36px);
        font-weight: 800;
        color: #222222;
        line-height: 1.25;
        letter-spacing: -.02em;
    }

    .detail-section {
        margin-top: 32px;
        padding-top: 28px;
        border-top: 1px solid #ebebeb;
    }
    .detail-section h2 {
        font-family: 'Outfit', sans-serif;
        font-size: 18px;
        font-weight: 800;
        color: #222222;
        margin-bottom: 12px;
    }
    .detail-section p {
        font-size: 15px;
        color: #555555;
        line-height: 1.7;
    }

    .fasilitas-clean {
        list-style: none;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .fasilitas-clean li {
        display: flex;
        gap: 12px;
        align-items: center;
        background: #f7f7f7;
        border: 1px solid #ebebeb;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 600;
        color: #222222;
    }
    .fasilitas-clean .tick {
        width: 22px;
        height: 22px;
        background: #222222;
        color: #ffffff;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 11px;
        font-weight: 900;
        flex-shrink: 0;
    }

    /* Sticky Booking Card */
    .detail-side {
        position: sticky;
        top: 96px;
    }
    .booking-card-clean {
        background: #ffffff;
        border: 1px solid #dddddd;
        border-radius: 20px;
        padding: 28px;
        box-shadow: var(--shadow-card);
    }
    .price-top {
        display: flex;
        align-items: baseline;
        gap: 6px;
    }
    .price-top .amount {
        font-family: 'Outfit', sans-serif;
        font-size: 32px;
        font-weight: 800;
        color: #222222;
    }
    .price-top .unit {
        font-size: 14px;
        color: #717171;
    }

    .booking-specs {
        display: grid;
        gap: 12px;
        margin: 22px 0;
        padding: 18px 0;
        border-top: 1px solid #ebebeb;
        border-bottom: 1px solid #ebebeb;
    }
    .spec-item {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
    }
    .spec-item span:first-child { color: #717171; }
    .spec-item span:last-child { color: #222222; font-weight: 700; text-align: right; }

    .note-clean {
        font-size: 12.5px;
        color: #717171;
        margin-top: 14px;
        line-height: 1.5;
        text-align: center;
    }

    @media (max-width: 980px) {
        .detail-grid { grid-template-columns: 1fr; }
        .detail-side { position: static; }
        .fasilitas-clean { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
    <div class="container breadcrumb-clean">
        <a href="{{ route('home') }}">Beranda</a> &nbsp;/&nbsp;
        <a href="{{ route('paket.index') }}">Katalog Paket</a> &nbsp;/&nbsp;
        <span>{{ $paket->judul }}</span>
    </div>

    <section class="container detail-grid">
        <div class="detail-main">
            <img src="{{ $paket->foto_url ?? asset('images/galeri/bali.webp') }}" alt="{{ $paket->judul }}" class="detail-photo">
            
            <div class="detail-body">
                <span class="dest-pill">{{ $paket->destinasi }}</span>
                <h1>{{ $paket->judul }}</h1>

                <div class="detail-section">
                    <h2>Deskripsi Perjalanan</h2>
                    <p>{{ $paket->deskripsi }}</p>
                </div>

                <div class="detail-section">
                    <h2>Fasilitas Termasuk</h2>
                    <ul class="fasilitas-clean">
                        @foreach (array_filter(array_map('trim', explode(',', $paket->fasilitas))) as $fasilitas)
                            <li>
                                <span class="tick">&#10003;</span>
                                <span>{{ $fasilitas }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <aside class="detail-side">
            <div class="booking-card-clean">
                <div class="price-top">
                    <span class="amount">Rp {{ number_format((float) $paket->harga, 0, ',', '.') }}</span>
                    <span class="unit">/ orang</span>
                </div>

                <div class="booking-specs">
                    <div class="spec-item">
                        <span>Destinasi</span>
                        <span>{{ $paket->destinasi }}</span>
                    </div>
                    <div class="spec-item">
                        <span>Armada Transportasi</span>
                        <span>{{ $paket->bus ?: 'Bus Pariwisata AC' }}</span>
                    </div>
                    <div class="spec-item">
                        <span>Status Kuota</span>
                        <span style="color:#008a05;">Tersedia &middot; Buka Booking</span>
                    </div>
                </div>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary btn-block">Masuk untuk Memesan</a>
                    <p class="note-clean">Belum memiliki akun? <a href="{{ route('register') }}" style="color:#222;font-weight:700;text-decoration:underline;">Daftar gratis</a></p>
                @else
                    @if (auth()->user()->isKonsumen())
                        <a href="{{ route('konsumen.pemesanan.create', $paket) }}" class="btn btn-primary btn-block">Pesan Paket Sekarang</a>
                        <p class="note-clean">Pemesanan online instan dengan konfirmasi kuitansi resmi.</p>
                    @else
                        <p class="note-clean">Anda masuk sebagai <strong>{{ auth()->user()->level }}</strong>. Pemesanan tiket diperuntukkan bagi akun konsumen.</p>
                    @endif
                @endguest
            </div>
        </aside>
    </section>
@endsection
