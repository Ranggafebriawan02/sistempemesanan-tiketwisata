@extends('layouts.app')

@section('title', 'PT. Puma Star Jaya — Pemesanan Paket Wisata')

@push('styles')
<style>
    /* ========== Traveloka Header Hero ========== */
    .hero {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #0194f3 0%, #007ce8 65%, #0264b1 100%);
        color: #ffffff;
        padding: 56px 0 88px;
    }
    .hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, .15) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: .4;
        pointer-events: none;
    }
    .hero .container { position: relative; z-index: 1; display: grid; grid-template-columns: 1.15fr .85fr; gap: 48px; align-items: center; }
    .hero-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, .2);
        border: 1px solid rgba(255, 255, 255, .35);
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .06em;
        padding: 6px 14px;
        border-radius: 999px;
        margin-bottom: 16px;
        backdrop-filter: blur(8px);
    }
    .hero h1 {
        font-size: clamp(30px, 4vw, 48px);
        line-height: 1.15;
        letter-spacing: -.025em;
        font-weight: 800;
        color: #ffffff;
    }
    .hero h1 em { font-style: normal; color: #ffeb3b; }
    .hero p { margin-top: 16px; max-width: 520px; color: #e1f3ff; font-size: 15px; line-height: 1.6; }
    .hero-actions { display: flex; gap: 14px; flex-wrap: wrap; margin-top: 28px; }
    .hero-note { margin-top: 24px; display: flex; gap: 20px; flex-wrap: wrap; color: #d4edff; font-size: 13px; font-weight: 600; }
    .hero-note span { display: inline-flex; align-items: center; gap: 7px; }
    .hero-note i {
        width: 18px; height: 18px; border-radius: 50%;
        background: #ffffff;
        color: #0194f3;
        display: inline-grid; place-items: center;
        font-style: normal; font-size: 11px; font-weight: 900;
    }

    /* Traveloka Search / Service Box Widget */
    .hero-widget {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 16px 40px rgba(3, 18, 38, 0.2);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.4);
    }
    .widget-tabs {
        display: flex;
        background: #f7f9fa;
        border-bottom: 1px solid #e5e9ed;
    }
    .widget-tab {
        flex: 1;
        padding: 14px 16px;
        font-size: 13px;
        font-weight: 700;
        color: var(--slate-700);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-bottom: 2px solid transparent;
        background: transparent;
    }
    .widget-tab.active {
        color: var(--traveloka-blue);
        background: #ffffff;
        border-bottom-color: var(--traveloka-blue);
    }
    .widget-tab svg { width: 18px; height: 18px; fill: currentColor; }
    .widget-body {
        padding: 24px;
        color: var(--slate-900);
    }
    .widget-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--slate-900);
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .widget-title span.badge {
        font-size: 11px;
        font-weight: 700;
        background: #e7f5ff;
        color: #0194f3;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .widget-features {
        list-style: none;
        display: grid;
        gap: 10px;
    }
    .widget-features li {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        background: #f8fafc;
        border: 1px solid #e5e9ed;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: var(--slate-700);
    }
    .widget-features li svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        color: #0194f3;
    }
    .widget-footer {
        margin-top: 16px;
    }

    /* Floating Stats Bar */
    .stats-strip {
        margin-top: -42px;
        position: relative;
        z-index: 2;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        background: #fff;
        border: 1px solid var(--slate-200);
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(3, 18, 38, .08);
        overflow: hidden;
    }
    .stat {
        padding: 20px 24px;
        border-right: 1px solid var(--slate-200);
    }
    .stat:last-child { border-right: 0; }
    .stat strong { display: block; font-size: 26px; letter-spacing: -.02em; color: var(--traveloka-blue); font-weight: 800; }
    .stat span { font-size: 12px; color: var(--slate-500); font-weight: 600; }

    /* ========== Paket Grid (Traveloka Card Style) ========== */
    .paket-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }
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
        background: linear-gradient(180deg, rgba(3, 18, 38, .3) 0%, transparent 40%, rgba(3, 18, 38, .7) 100%);
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
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .paket-body { padding: 18px 20px; display: flex; flex-direction: column; flex: 1; }
    .paket-body h3 { font-size: 16px; font-weight: 800; line-height: 1.35; letter-spacing: -.01em; color: var(--slate-900); }
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

    /* About Section */
    .about {
        display: grid;
        grid-template-columns: .95fr 1.05fr;
        gap: 48px;
        align-items: center;
    }
    .about-visual {
        border-radius: 18px;
        min-height: 320px;
        background: linear-gradient(135deg, #0194f3, #0264b1);
        position: relative;
        overflow: hidden;
        display: grid;
        place-items: center;
        color: #ffffff;
        text-align: center;
        padding: 30px;
        box-shadow: 0 12px 30px rgba(1, 148, 243, 0.25);
    }
    .about-visual::before {
        content: "";
        position: absolute; inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, .15) 1px, transparent 1px);
        background-size: 20px 20px;
    }
    .about-visual div { position: relative; z-index: 1; }
    .about-visual strong { display: block; font-size: 52px; color: #ffeb3b; letter-spacing: -.03em; font-weight: 900; }
    .about-visual span { font-size: 14px; letter-spacing: .06em; text-transform: uppercase; font-weight: 700; }
    .about-list { list-style: none; display: grid; gap: 12px; margin-top: 22px; }
    .about-list li {
        display: flex; gap: 12px; align-items: flex-start;
        font-size: 13.5px; color: var(--slate-700);
    }
    .about-list .num {
        flex-shrink: 0; width: 26px; height: 26px; border-radius: 8px;
        background: #e7f5ff; color: #0194f3;
        border: 1px solid #b9e3ff;
        display: grid; place-items: center;
        font-size: 12px; font-weight: 800;
    }

    /* Galeri Grid */
    .galeri-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    .galeri-item {
        height: 220px;
        border-radius: 14px;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 16px 18px;
        color: #fff;
        background-size: cover;
        background-position: center;
        box-shadow: 0 4px 14px rgba(3, 18, 38, .08);
        border: 1px solid rgba(255, 255, 255, .15);
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .galeri-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 28px rgba(3, 18, 38, .2);
    }
    .galeri-item::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 30%, rgba(3, 18, 38, .85) 100%);
    }
    .galeri-item .galeri-text { position: relative; z-index: 2; }
    .galeri-item .galeri-text strong {
        display: block; font-size: 15px; font-weight: 800; color: #fff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, .4);
    }
    .galeri-item .galeri-text span {
        display: block; font-size: 12px; color: #d7efff; margin-top: 2px; font-weight: 500;
    }

    /* Contact Card */
    .contact-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
    .contact-card {
        background: #fff;
        border: 1px solid var(--slate-200);
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 2px 6px rgba(3, 18, 38, 0.03);
    }
    .contact-card h4 { font-size: 12px; text-transform: uppercase; letter-spacing: .08em; color: var(--traveloka-blue); font-weight: 800; }
    .contact-card p { margin-top: 8px; font-size: 14px; color: var(--slate-900); font-weight: 600; line-height: 1.5; }

    /* CTA Section */
    .cta {
        margin-top: 52px;
        border-radius: 18px;
        padding: 40px 44px;
        background: linear-gradient(120deg, #0194f3, #007ce8);
        color: #ffffff;
        display: flex; align-items: center; justify-content: space-between; gap: 24px; flex-wrap: wrap;
        box-shadow: 0 16px 36px rgba(1, 148, 243, 0.28);
    }
    .cta h3 { font-size: 24px; letter-spacing: -.02em; color: #fff; font-weight: 800; }
    .cta p { margin-top: 6px; font-size: 14px; color: #e1f3ff; }

    @media (max-width: 980px) {
        .hero .container { grid-template-columns: 1fr; gap: 36px; }
        .hero-widget { max-width: 520px; }
        .paket-grid { grid-template-columns: repeat(2, 1fr); }
        .about { grid-template-columns: 1fr; gap: 28px; }
        .galeri-grid { grid-template-columns: repeat(2, 1fr); }
        .contact-grid { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .stat:nth-child(2) { border-right: 0; }
        .stat:nth-child(1), .stat:nth-child(2) { border-bottom: 1px solid var(--slate-200); }
    }
    @media (max-width: 640px) {
        .paket-grid, .galeri-grid { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: 1fr; }
        .stat { border-right: 0; border-bottom: 1px solid var(--slate-200); }
        .stat:last-child { border-bottom: 0; }
        .cta { padding: 28px 24px; }
    }
</style>
@endpush

@section('content')
    <section class="hero">
        <div class="container">
            <div>
                <span class="hero-tag">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                    RESMI &middot; BIRO PERJALANAN SEJAK 2016
                </span>
                <h1>Mau ke Mana? Temukan <em>Paket Wisata Impianmu</em></h1>
                <p>Pesan paket perjalanan wisata nusantara, study tour, kunjungan industri, KKL, dan ziarah Wali Songo bersama PT. Puma Star Jaya. Nikmati kemudahan reservasi tiket, pembayaran fleksibel, dan kuitansi instan langsung dari website.</p>
                <div class="hero-actions">
                    <a href="{{ route('paket.index') }}" class="btn btn-amber">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        Cari Paket Wisata
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-light">Daftar Akun Gratis</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-light">Buka Dashboard Saya</a>
                    @endguest
                </div>
                <div class="hero-note">
                    <span><i>&#10003;</i> Jaminan Harga Terbaik</span>
                    <span><i>&#10003;</i> Armada Bus Pariwisata Nyaman</span>
                    <span><i>&#10003;</i> Pemandu Wisata Profesional</span>
                </div>
            </div>

            <!-- Traveloka Search / Booking Box Widget -->
            <div class="hero-widget">
                <div class="widget-tabs">
                    <div class="widget-tab active">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2.5 19.5 21 12 2.5 4.5l3 7.5-3 7.5Z"/><path d="M5.5 12H21"/></svg>
                        Paket Wisata &amp; Tour
                    </div>
                    <div class="widget-tab">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        Reservasi Cepat
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-title">
                        <span>Layanan Utama Tour &amp; Travel</span>
                        <span class="badge">Terpercaya</span>
                    </div>
                    <ul class="widget-features">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/></svg>
                            <div><strong>Paket Wisata Nusantara:</strong> Bali, Yogyakarta, Danau Toba, Bandung, Jakarta</div>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                            <div><strong>Instansi &amp; Akademik:</strong> Study Tour, Kunjungan Industri, KKL</div>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <div><strong>Wisata Religi:</strong> Ziarah Wali Songo Jawa Timur &amp; Jawa Tengah</div>
                        </li>
                    </ul>
                    <div class="widget-footer">
                        <a href="{{ route('paket.index') }}" class="btn btn-primary btn-block">
                            Eksplorasi Katalog Wisata &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container stats-strip">
        <div class="stats-grid">
            <div class="stat"><strong>2016</strong><span>Tahun Berdiri di Lampung</span></div>
            <div class="stat"><strong>24</strong><span>Staff &amp; Pemandu Profesional</span></div>
            <div class="stat"><strong>{{ $paket->count() }}+</strong><span>Destinasi Wisata Pilihan</span></div>
            <div class="stat"><strong>100%</strong><span>Reservasi Online Mudah</span></div>
        </div>
    </div>

    <section class="page-section" id="paket">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow" style="color:var(--traveloka-blue); font-weight:800;">PROMO &amp; DESTINASI POPULER</span>
                <h2 class="section-title">Rekomendasi Paket Wisata Terbaik</h2>
                <p class="section-desc">Pilihan destinasi favorit dengan fasilitas terpadu: transportasi bus eksklusif, penginapan nyaman, konsumsi harian, dan tiket masuk objek wisata.</p>
            </div>

            <div class="paket-grid">
                @forelse ($paket as $item)
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
                            <p>{{ \Illuminate\Support\Str::limit($item->deskripsi, 105) }}</p>
                            <div class="paket-meta">
                                @if ($item->bus)
                                    <span class="chip chip-bus">🚌 {{ $item->bus }}</span>
                                @endif
                                <span class="chip">✨ {{ $item->fasilitas ? \Illuminate\Support\Str::limit($item->fasilitas, 30) : 'Fasilitas lengkap' }}</span>
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
                @empty
                    <p>Belum ada paket wisata yang tersedia.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="page-section" id="tentang" style="background:#fff; border-top:1px solid var(--slate-200); border-bottom:1px solid var(--slate-200);">
        <div class="container about">
            <div class="about-visual">
                <div>
                    <strong>10+</strong>
                    <span>Tahun melayani perjalanan</span>
                </div>
            </div>
            <div>
                <span class="eyebrow">Tentang Kami</span>
                <h2 class="section-title">Biro perjalanan wisata yang tumbuh bersama kepercayaan pelanggan</h2>
                <p class="section-desc">PT. Puma Star Jaya berdiri sejak 10 Agustus 2016 dan berkedudukan di Jl. Sultan Dumas No. A8&ndash;A9, Metro Selatan, Kota Metro, Lampung. Kami didukung 24 karyawan yang berpengalaman di bidang wisata rohani maupun wisata umum.</p>
                <ul class="about-list">
                    <li><span class="num">1</span><span>Informasi paket wisata yang lengkap dan mudah diakses.</span></li>
                    <li><span class="num">2</span><span>Pemesanan online tanpa harus datang ke kantor.</span></li>
                    <li><span class="num">3</span><span>Konfirmasi pembayaran dan kuitansi yang tertata.</span></li>
                    <li><span class="num">4</span><span>Laporan pemesanan dan pembayaran yang cepat dan akurat.</span></li>
                </ul>
            </div>
        </div>
    </section>

    <section class="page-section" id="galeri">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Galeri</span>
                <h2 class="section-title">Destinasi yang kami layani</h2>
                <p class="section-desc">Sebagian destinasi populer dalam paket perjalanan PT. Puma Star Jaya.</p>
            </div>
            <div class="galeri-grid">
                <div class="galeri-item" style="background-image: url('{{ asset('images/galeri/walisa.jpg') }}');">
                    <div class="galeri-text">
                        <strong>Ziarah Wali Songo</strong>
                        <span>Wisata Rohani &amp; Makam Para Wali</span>
                    </div>
                </div>
                <div class="galeri-item" style="background-image: url('{{ asset('images/galeri/bali.webp') }}');">
                    <div class="galeri-text">
                        <strong>Study Tour Bali</strong>
                        <span>Pantai, Budaya &amp; Edukasi Pulau Dewata</span>
                    </div>
                </div>
                <div class="galeri-item" style="background-image: url('{{ asset('images/galeri/yogya.jpg') }}');">
                    <div class="galeri-text">
                        <strong>Yogyakarta Istimewa</strong>
                        <span>Candi, Keraton, &amp; Kunjungan Industri</span>
                    </div>
                </div>
                <div class="galeri-item" style="background-image: url('{{ asset('images/galeri/toba.jpg') }}');">
                    <div class="galeri-text">
                        <strong>Pesona Danau Toba</strong>
                        <span>Keindahan Alam Danau Vulkanik Terbesar</span>
                    </div>
                </div>
                <div class="galeri-item" style="background-image: url('{{ asset('images/galeri/jkt.jpg') }}');">
                    <div class="galeri-text">
                        <strong>Study Banding Jakarta</strong>
                        <span>Kunjungan Instansi &amp; Wisata Modern Ibu Kota</span>
                    </div>
                </div>
                <div class="galeri-item" style="background-image: url('{{ asset('images/galeri/bdh.jpg') }}');">
                    <div class="galeri-text">
                        <strong>Wisata Bandung</strong>
                        <span>Kesejukan Alam &amp; Wisata Edukasi Kuliner</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section" id="kontak" style="padding-top:0;">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Kontak</span>
                <h2 class="section-title">Butuh bantuan merencanakan perjalanan?</h2>
                <p class="section-desc">Hubungi kami melalui kanal berikut atau daftar akun untuk memesan secara online.</p>
            </div>
            <div class="contact-grid">
                <div class="contact-card">
                    <h4>Alamat</h4>
                    <p>Jl. Sultan Dumas No. A8&ndash;A9, Metro Selatan, Kota Metro, Lampung</p>
                </div>
                <div class="contact-card">
                    <h4>Telepon / WhatsApp</h4>
                    <p>0812-3456-7890</p>
                </div>
                <div class="contact-card">
                    <h4>Email</h4>
                    <p>info@pumastarjaya.test</p>
                </div>
            </div>

            <div class="cta">
                <div>
                    <h3>Siap memesan paket wisata?</h3>
                    <p>Buat akun konsumen, pilih paket, lalu unggah bukti pembayaran Anda.</p>
                </div>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-amber">Daftar Sekarang</a>
                @else
                    <a href="{{ route('paket.index') }}" class="btn btn-amber">Pilih Paket</a>
                @endguest
            </div>
        </div>
    </section>
@endsection
