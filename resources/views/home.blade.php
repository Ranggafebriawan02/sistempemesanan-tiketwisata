@extends('layouts.app')

@section('title', 'Puma Star Jaya — Perjalanan & Paket Wisata Eksklusif')

@push('styles')
<style>
    /* Scandinavian / Airbnb Clean Aesthetic */
    .hero-clean {
        background: #ffffff;
        padding: 48px 0 24px;
        text-align: center;
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f7f7f7;
        border: 1px solid #ebebeb;
        padding: 8px 18px;
        border-radius: var(--radius-full);
        font-size: 13px;
        font-weight: 700;
        color: #222222;
        margin-bottom: 24px;
    }
    .hero-clean h1 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(34px, 4.8vw, 56px);
        font-weight: 800;
        letter-spacing: -.03em;
        line-height: 1.12;
        color: #222222;
        max-width: 840px;
        margin: 0 auto;
    }
    .hero-clean h1 span.gradient-text {
        background: linear-gradient(135deg, #ff385c 0%, #e00b41 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .hero-clean p {
        font-size: 17px;
        color: #717171;
        max-width: 640px;
        margin: 18px auto 0;
        line-height: 1.6;
    }

    /* Floating Interactive Airbnb Search Bar */
    .search-clean-wrapper {
        margin: 36px auto 0;
        max-width: 900px;
    }
    .search-clean-bar {
        background: #ffffff;
        border: 1px solid #dddddd;
        border-radius: var(--radius-full);
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.08);
        display: grid;
        grid-template-columns: 1.4fr 1.1fr 1fr auto;
        padding: 8px 10px 8px 24px;
        align-items: center;
        gap: 12px;
        transition: box-shadow .2s;
    }
    .search-clean-bar:hover {
        box-shadow: 0 12px 36px rgba(0, 0, 0, 0.12);
    }
    .clean-col {
        display: flex;
        flex-direction: column;
        text-align: left;
        padding-right: 12px;
        border-right: 1px solid #ebebeb;
    }
    .clean-col:last-of-type { border-right: none; }
    .clean-col label {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #222222;
    }
    .clean-col input, .clean-col select {
        border: none;
        background: transparent;
        font-family: inherit;
        font-size: 14px;
        color: #717171;
        outline: none;
        width: 100%;
        padding-top: 2px;
        font-weight: 500;
    }
    .clean-col select { cursor: pointer; }
    .btn-clean-search {
        background: #ff385c;
        color: #fff;
        border: none;
        border-radius: var(--radius-full);
        width: 48px;
        height: 48px;
        display: grid;
        place-items: center;
        cursor: pointer;
        transition: transform .15s, background .15s;
    }
    .btn-clean-search:hover {
        background: #e00b41;
        transform: scale(1.05);
    }
    .btn-clean-search svg { width: 20px; height: 20px; }

    /* Category Filter Pills (Airbnb Experience Bar) */
    .filter-pills {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin: 36px 0 10px;
        flex-wrap: wrap;
    }
    .pill-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: var(--radius-full);
        background: #ffffff;
        border: 1px solid #dddddd;
        font-size: 13.5px;
        font-weight: 600;
        color: #222222;
        transition: all .15s;
    }
    .pill-btn:hover, .pill-btn.active {
        background: #222222;
        border-color: #222222;
        color: #ffffff;
    }

    /* Minimalist Photo-Centric Cards */
    .catalog-section {
        padding: 40px 0 70px;
    }
    .catalog-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 28px;
    }
    .catalog-header h2 {
        font-family: 'Outfit', sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: #222222;
        letter-spacing: -.02em;
    }
    .catalog-header p { font-size: 14.5px; color: #717171; margin-top: 4px; }

    .grid-airbnb {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 32px 24px;
    }
    .airbnb-card {
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        transition: transform .2s ease;
    }
    .airbnb-card:hover {
        transform: translateY(-4px);
    }
    .card-photo-box {
        position: relative;
        width: 100%;
        height: 250px;
        border-radius: 16px;
        overflow: hidden;
        background: #ebebeb;
        margin-bottom: 14px;
    }
    .card-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s ease;
    }
    .airbnb-card:hover .card-photo {
        transform: scale(1.04);
    }
    .dest-pill-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        background: rgba(255, 255, 255, 0.95);
        color: #222222;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: var(--radius-full);
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .card-meta-top {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 10px;
    }
    .card-title {
        font-size: 16px;
        font-weight: 700;
        color: #222222;
        line-height: 1.35;
    }
    .card-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 13px;
        font-weight: 700;
        color: #222222;
        flex-shrink: 0;
    }
    .card-bus-info {
        font-size: 13.5px;
        color: #717171;
        margin-top: 4px;
    }
    .card-facility {
        font-size: 12.5px;
        color: #888888;
        margin-top: 2px;
    }
    .card-price-bottom {
        margin-top: 10px;
        display: flex;
        align-items: baseline;
        gap: 6px;
    }
    .card-price {
        font-size: 17px;
        font-weight: 800;
        color: #222222;
    }
    .card-price span {
        font-size: 13px;
        font-weight: 400;
        color: #717171;
    }

    /* Trust & About Banner */
    .trust-clean {
        background: #ffffff;
        border-top: 1px solid #ebebeb;
        border-bottom: 1px solid #ebebeb;
        padding: 60px 0;
        margin: 40px 0;
    }
    .trust-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
    }
    .trust-item {
        display: flex;
        gap: 18px;
        align-items: flex-start;
    }
    .trust-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #f7f7f7;
        border: 1px solid #ebebeb;
        display: grid;
        place-items: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .trust-info h4 {
        font-family: 'Outfit', sans-serif;
        font-size: 17px;
        font-weight: 700;
        color: #222222;
        margin-bottom: 6px;
    }
    .trust-info p {
        font-size: 14px;
        color: #717171;
        line-height: 1.55;
    }

    @media (max-width: 980px) {
        .search-clean-bar { grid-template-columns: 1fr 1fr; border-radius: 20px; padding: 16px; }
        .clean-col { border-right: none; margin-bottom: 8px; }
        .grid-airbnb { grid-template-columns: repeat(2, 1fr); }
        .trust-grid { grid-template-columns: 1fr; gap: 28px; }
    }
    @media (max-width: 640px) {
        .grid-airbnb { grid-template-columns: 1fr; }
        .search-clean-bar { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
    <!-- Scandinavian Clean Hero -->
    <section class="hero-clean">
        <div class="container">
            <div class="hero-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ff385c" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span>Biro Wisata Terpercaya Sejak 2016 &middot; Metro, Lampung</span>
            </div>
            <h1>Jelajahi Pengalaman Nusantara Tanpa Batas bersama <span class="gradient-text">Puma Star Jaya</span></h1>
            <p>Pesan paket perjalanan wisata, study tour, ziarah, dan KKL kampus dengan proses mudah, armada bus pariwisata premium, dan harga transparan.</p>

            <!-- Airbnb Style Floating Search -->
            <div class="search-clean-wrapper">
                <form action="{{ route('paket.index') }}" method="GET" class="search-clean-bar">
                    <div class="clean-col">
                        <label>Destinasi Wisata</label>
                        <select name="dest">
                            <option value="">Mau pergi ke mana?</option>
                            <option value="Bali">Pulau Dewata Bali</option>
                            <option value="Yogyakarta">Yogyakarta &amp; Candi</option>
                            <option value="Jawa">Ziarah Wali Songo</option>
                            <option value="Sumatera Utara">Danau Toba &amp; Samosir</option>
                            <option value="Bandung">Bandung Kota Kembang</option>
                            <option value="Jakarta">DKI Jakarta</option>
                        </select>
                    </div>

                    <div class="clean-col">
                        <label>Waktu Berangkat</label>
                        <input type="date" name="tgl" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="clean-col">
                        <label>Siapa yang Ikut?</label>
                        <select name="kategori">
                            <option value="">Rombongan / Keluarga</option>
                            <option value="rombongan">Pelajar / Mahasiswa</option>
                            <option value="pengajian">Jamaah Pengajian</option>
                            <option value="kantor">Instansi / Perusahaan</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-clean-search" aria-label="Cari">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </button>
                </form>
            </div>

            <!-- Categories Quick Navigation -->
            <div class="filter-pills">
                <a href="{{ route('paket.index') }}" class="pill-btn active">Semua Destinasi</a>
                <a href="{{ route('paket.index') }}?dest=Bali" class="pill-btn">🌴 Study Tour Bali</a>
                <a href="{{ route('paket.index') }}?dest=Yogyakarta" class="pill-btn">🏛️ KKL Yogyakarta</a>
                <a href="{{ route('paket.index') }}?dest=Jawa" class="pill-btn">🕌 Ziarah Wali Songo</a>
                <a href="{{ route('paket.index') }}?dest=Sumatera" class="pill-btn">🌊 Danau Toba</a>
            </div>
        </div>
    </section>

    <!-- Catalog Section (Airbnb Clean Grid) -->
    <section class="container catalog-section" id="paket">
        <div class="catalog-header">
            <div>
                <h2>Destinasi &amp; Paket Wisata Unggulan</h2>
                <p>Dipilih dan disusun khusus untuk kenyamanan liburan serta kegiatan akademik Anda.</p>
            </div>
            <a href="{{ route('paket.index') }}" class="btn btn-outline btn-sm">Lihat Semua Paket ({{ $paket->count() }})</a>
        </div>

        <div class="grid-airbnb">
            @forelse ($paket as $item)
                <a href="{{ route('paket.show', $item) }}" class="airbnb-card">
                    <div class="card-photo-box">
                        <img src="{{ $item->foto_url ?? asset('images/galeri/bali.webp') }}" alt="{{ $item->judul }}" class="card-photo" loading="lazy">
                        <span class="dest-pill-badge">{{ $item->destinasi }}</span>
                    </div>
                    <div class="card-meta-top">
                        <h3 class="card-title">{{ $item->judul }}</h3>
                        <span class="card-rating">★ 4.94</span>
                    </div>
                    <div class="card-bus-info">🚌 {{ $item->bus ?: 'Armada Pariwisata Nyaman' }}</div>
                    <div class="card-facility">{{ $item->fasilitas ? \Illuminate\Support\Str::limit($item->fasilitas, 48) : 'Fasilitas lengkap penginapan & makan' }}</div>
                    <div class="card-price-bottom">
                        <div class="card-price">Rp {{ number_format((float) $item->harga, 0, ',', '.') }}</div>
                        <span>/ orang</span>
                    </div>
                </a>
            @empty
                <p>Belum ada paket wisata.</p>
            @endforelse
        </div>
    </section>

    <!-- Trust Values Strip (Scandinavian Clean) -->
    <section class="trust-clean" id="tentang">
        <div class="container">
            <div class="trust-grid">
                <div class="trust-item">
                    <div class="trust-icon">🛡️</div>
                    <div class="trust-info">
                        <h4>Berizin Resmi &amp; Terpercaya</h4>
                        <p>Berdiri sejak 10 Agustus 2016 di Kota Metro, Lampung dengan legalitas lengkap dan 24 karyawan profesional.</p>
                    </div>
                </div>

                <div class="trust-item">
                    <div class="trust-icon">🚍</div>
                    <div class="trust-info">
                        <h4>Armada Bus Pariwisata Prima</h4>
                        <p>Unit Big Bus dan Medium Bus AC dengan perawatan rutin, audio visual, reclining seats, dan kru sopir berpengalaman.</p>
                    </div>
                </div>

                <div class="trust-item">
                    <div class="trust-icon">💳</div>
                    <div class="trust-info">
                        <h4>Pemesanan &amp; Kuitansi Instan</h4>
                        <p>Reservasi online langsung terbit kode pemesanan, validasi transaksi oleh bendahara, dan kuitansi resmi sistem.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Galeri Preview Section -->
    <section class="container" id="galeri" style="padding: 40px 28px 70px;">
        <div class="catalog-header">
            <div>
                <h2>Galeri Perjalanan Bersama Kami</h2>
                <p>Dokumentasi perjalanan rombongan sekolah, instansi, dan jamaah ziarah.</p>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4, 1fr);gap:16px;">
            <div style="height:200px;border-radius:16px;overflow:hidden;background:#ebebeb;">
                <img src="{{ asset('images/galeri/bali.webp') }}" alt="Bali" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div style="height:200px;border-radius:16px;overflow:hidden;background:#ebebeb;">
                <img src="{{ asset('images/galeri/yogya.jpg') }}" alt="Yogya" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div style="height:200px;border-radius:16px;overflow:hidden;background:#ebebeb;">
                <img src="{{ asset('images/galeri/walisa.jpg') }}" alt="Wali Songo" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div style="height:200px;border-radius:16px;overflow:hidden;background:#ebebeb;">
                <img src="{{ asset('images/galeri/toba.jpg') }}" alt="Danau Toba" style="width:100%;height:100%;object-fit:cover;">
            </div>
        </div>
    </section>
@endsection
