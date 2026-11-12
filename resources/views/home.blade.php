@extends('layouts.app')

@section('title', 'PT. Puma Star Jaya — Pemesanan Paket Wisata')

@push('styles')
<style>
    .hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(1100px 500px at 85% -10%, rgba(13, 148, 136, .5), transparent 60%),
            radial-gradient(900px 500px at -10% 110%, rgba(245, 158, 11, .22), transparent 55%),
            linear-gradient(135deg, #0b3d3a 0%, #0f4c46 55%, #08211f 100%);
        color: #eafffb;
        padding: 78px 0 92px;
    }
    .hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, .08) 1px, transparent 1px);
        background-size: 26px 26px;
        opacity: .35;
        pointer-events: none;
    }
    .hero .container { position: relative; z-index: 1; display: grid; grid-template-columns: 1.15fr .85fr; gap: 56px; align-items: center; }
    .hero-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, .1);
        border: 1px solid rgba(255, 255, 255, .22);
        color: #ccfbf1;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .06em;
        padding: 6px 12px;
        border-radius: 999px;
        margin-bottom: 18px;
    }
    .hero h1 {
        font-size: clamp(32px, 4.4vw, 54px);
        line-height: 1.08;
        letter-spacing: -.03em;
        font-weight: 800;
        color: #fff;
    }
    .hero h1 em { font-style: normal; color: #fcd34d; }
    .hero p { margin-top: 18px; max-width: 540px; color: #c7e8e3; font-size: 15.5px; }
    .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 30px; }
    .hero-note { margin-top: 26px; display: flex; gap: 22px; flex-wrap: wrap; color: #9ac9c3; font-size: 12.5px; }
    .hero-note span { display: inline-flex; align-items: center; gap: 7px; }
    .hero-note i {
        width: 18px; height: 18px; border-radius: 50%;
        background: rgba(94, 234, 212, .18);
        color: #5eead4;
        display: inline-grid; place-items: center;
        font-style: normal; font-size: 11px; font-weight: 800;
    }

    .hero-card {
        background: rgba(255, 255, 255, .07);
        border: 1px solid rgba(255, 255, 255, .16);
        border-radius: 20px;
        padding: 22px;
        backdrop-filter: blur(12px);
        box-shadow: var(--shadow-lg);
    }
    .hero-card h3 { font-size: 13px; text-transform: uppercase; letter-spacing: .1em; color: #9ac9c3; margin-bottom: 14px; }
    .hero-card ul { list-style: none; display: grid; gap: 10px; }
    .hero-card li {
        display: flex; align-items: flex-start; gap: 10px;
        font-size: 13.5px; color: #eafffb;
        background: rgba(255, 255, 255, .06);
        border: 1px solid rgba(255, 255, 255, .08);
        padding: 11px 13px; border-radius: 11px;
    }
    .hero-card li b { color: #fcd34d; font-size: 12px; }

    .stats-strip {
        margin-top: -46px;
        position: relative;
        z-index: 2;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        background: #fff;
        border: 1px solid var(--slate-200);
        border-radius: 18px;
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    .stat {
        padding: 24px 26px;
        border-right: 1px solid var(--slate-200);
    }
    .stat:last-child { border-right: 0; }
    .stat strong { display: block; font-size: 26px; letter-spacing: -.02em; color: var(--teal-800); }
    .stat span { font-size: 12.5px; color: var(--slate-500); font-weight: 600; }

    .paket-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }
    .paket-card {
        background: #fff;
        border: 1px solid var(--slate-200);
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform .2s, box-shadow .2s, border-color .2s;
    }
    .paket-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow);
        border-color: #99f6e4;
    }
    .paket-media {
        height: 132px;
        position: relative;
        display: flex;
        align-items: flex-end;
        padding: 14px 16px;
        color: #fff;
    }
    .paket-media::after {
        content: "";
        position: absolute; inset: 0;
        background: linear-gradient(180deg, transparent 20%, rgba(8, 33, 31, .55));
    }
    .paket-media .dest {
        position: relative; z-index: 1;
        font-size: 12px; font-weight: 700;
        background: rgba(255, 255, 255, .18);
        border: 1px solid rgba(255, 255, 255, .3);
        padding: 4px 10px; border-radius: 999px;
        backdrop-filter: blur(6px);
    }
    .paket-body { padding: 18px; display: flex; flex-direction: column; flex: 1; }
    .paket-body h3 { font-size: 16px; line-height: 1.35; letter-spacing: -.01em; }
    .paket-body p { margin-top: 8px; font-size: 12.5px; color: var(--slate-500); flex: 1; }
    .paket-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-top: 12px; }
    .chip {
        font-size: 11px; font-weight: 700;
        background: var(--teal-50);
        color: var(--teal-700);
        border: 1px solid #99f6e4;
        padding: 3px 9px; border-radius: 999px;
    }
    .paket-foot {
        display: flex; align-items: center; justify-content: space-between;
        gap: 10px; margin-top: 16px; padding-top: 14px;
        border-top: 1px dashed var(--slate-200);
    }
    .price { font-size: 16px; font-weight: 800; color: var(--teal-800); letter-spacing: -.02em; }
    .price small { display: block; font-size: 10.5px; color: var(--slate-500); font-weight: 600; letter-spacing: 0; }

    .about {
        display: grid;
        grid-template-columns: .95fr 1.05fr;
        gap: 48px;
        align-items: center;
    }
    .about-visual {
        border-radius: 20px;
        min-height: 340px;
        background:
            radial-gradient(500px 260px at 20% 10%, rgba(13, 148, 136, .35), transparent),
            linear-gradient(150deg, #0f4c46, #08211f);
        position: relative;
        overflow: hidden;
        display: grid;
        place-items: center;
        color: #d7fbf4;
        text-align: center;
        padding: 30px;
    }
    .about-visual::after {
        content: "";
        position: absolute; inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, .1) 1px, transparent 1px);
        background-size: 22px 22px;
        opacity: .5;
    }
    .about-visual div { position: relative; z-index: 1; }
    .about-visual strong { display: block; font-size: 44px; color: #fcd34d; letter-spacing: -.03em; }
    .about-visual span { font-size: 13px; letter-spacing: .06em; text-transform: uppercase; }
    .about-list { list-style: none; display: grid; gap: 12px; margin-top: 22px; }
    .about-list li {
        display: flex; gap: 12px; align-items: flex-start;
        font-size: 13.5px; color: var(--slate-700);
    }
    .about-list .num {
        flex-shrink: 0; width: 26px; height: 26px; border-radius: 8px;
        background: var(--teal-50); color: var(--teal-700);
        border: 1px solid #99f6e4;
        display: grid; place-items: center;
        font-size: 11.5px; font-weight: 800;
    }

    .galeri-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    .galeri-item {
        height: 220px;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 16px 18px;
        color: #fff;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .08);
        border: 1px solid rgba(255, 255, 255, .15);
        transition: transform .25s ease, box-shadow .25s ease;
        cursor: pointer;
    }
    .galeri-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 28px -6px rgba(15, 23, 42, .25);
    }
    .galeri-item::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(8, 33, 31, 0.1) 20%, rgba(8, 33, 31, .85) 100%);
        transition: opacity .25s ease;
    }
    .galeri-item:hover::after {
        background: linear-gradient(180deg, rgba(8, 33, 31, 0.05) 10%, rgba(8, 33, 31, .9) 100%);
    }
    .galeri-item .galeri-text {
        position: relative;
        z-index: 2;
    }
    .galeri-item .galeri-text strong {
        display: block;
        font-size: 15px;
        font-weight: 800;
        letter-spacing: -.01em;
        color: #fff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, .4);
    }
    .galeri-item .galeri-text span {
        display: block;
        font-size: 11.5px;
        color: #ccfbf1;
        margin-top: 2px;
        font-weight: 500;
    }

    .contact-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
    .contact-card {
        background: #fff;
        border: 1px solid var(--slate-200);
        border-radius: 14px;
        padding: 20px;
    }
    .contact-card h4 { font-size: 13px; text-transform: uppercase; letter-spacing: .08em; color: var(--slate-500); }
    .contact-card p { margin-top: 8px; font-size: 14px; color: var(--slate-900); font-weight: 600; }

    .cta {
        margin-top: 52px;
        border-radius: 20px;
        padding: 38px 40px;
        background: linear-gradient(120deg, var(--teal-800), var(--teal-700));
        color: #eafffb;
        display: flex; align-items: center; justify-content: space-between; gap: 24px; flex-wrap: wrap;
    }
    .cta h3 { font-size: 24px; letter-spacing: -.02em; color: #fff; }
    .cta p { margin-top: 6px; font-size: 13.5px; color: #c7e8e3; }

    @media (max-width: 980px) {
        .hero .container { grid-template-columns: 1fr; gap: 36px; }
        .hero-card { max-width: 520px; }
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
                <span class="hero-tag">SEJAK 2016 &middot; METRO, LAMPUNG</span>
                <h1>Jelajahi Nusantara,<br><em>Pesan Paket Wisata</em> Lebih Mudah.</h1>
                <p>PT. Puma Star Jaya melayani perjalanan wisata, study tour, kunjungan industri, KKL, study banding, dan ziarah Wali Songo. Pesan paket pilihan Anda secara online, unggah bukti pembayaran, dan pantau statusnya tanpa harus datang ke kantor.</p>
                <div class="hero-actions">
                    <a href="{{ route('paket.index') }}" class="btn btn-amber">Lihat Paket Wisata</a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-light">Daftar Akun</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-light">Buka Dashboard</a>
                    @endguest
                </div>
                <div class="hero-note">
                    <span><i>&#10003;</i> Harga terjangkau</span>
                    <span><i>&#10003;</i> Fasilitas lengkap</span>
                    <span><i>&#10003;</i> Pemandu berpengalaman</span>
                </div>
            </div>

            <aside class="hero-card">
                <h3>Layanan Kami</h3>
                <ul>
                    <li><b>01</b> Paket wisata seluruh nusantara</li>
                    <li><b>02</b> Kunjungan kerja &amp; kunjungan industri</li>
                    <li><b>03</b> Kuliah kerja lapangan &amp; study tour</li>
                    <li><b>04</b> Study banding &amp; ziarah Wali Songo</li>
                </ul>
            </aside>
        </div>
    </section>

    <div class="container stats-strip">
        <div class="stats-grid">
            <div class="stat"><strong>2016</strong><span>Tahun berdiri</span></div>
            <div class="stat"><strong>24</strong><span>Karyawan profesional</span></div>
            <div class="stat"><strong>{{ $paket->count() }}+</strong><span>Paket wisata tersedia</span></div>
            <div class="stat"><strong>100%</strong><span>Pelayanan terarah</span></div>
        </div>
    </div>

    <section class="page-section" id="paket">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Paket Wisata</span>
                <h2 class="section-title">Pilih perjalanan yang paling pas untuk Anda</h2>
                <p class="section-desc">Beragam paket wisata dengan fasilitas lengkap: transportasi, penginapan, konsumsi, pemandu, dan tiket objek wisata.</p>
            </div>

            <div class="paket-grid">
                @forelse ($paket as $item)
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
