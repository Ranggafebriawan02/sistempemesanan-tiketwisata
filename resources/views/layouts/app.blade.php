<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PT. Puma Star Jaya — Pemesanan Paket Wisata')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal-900: #0b3d3a;
            --teal-800: #0f4c46;
            --teal-700: #0f766e;
            --teal-600: #0d9488;
            --teal-100: #ccfbf1;
            --teal-50: #f0fdfa;
            --amber-500: #f59e0b;
            --amber-100: #fef3c7;
            --slate-900: #0f172a;
            --slate-700: #334155;
            --slate-500: #64748b;
            --slate-300: #cbd5e1;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
            --slate-50: #f8fafc;
            --white: #ffffff;
            --danger: #dc2626;
            --success: #16a34a;
            --radius: 14px;
            --shadow-sm: 0 1px 2px rgba(15, 23, 42, .06);
            --shadow: 0 10px 30px -12px rgba(15, 23, 42, .25);
            --shadow-lg: 0 24px 60px -20px rgba(15, 23, 42, .35);
            --container: 1180px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: var(--slate-50);
            color: var(--slate-900);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; }
        img { max-width: 100%; display: block; }

        .container {
            width: 100%;
            max-width: var(--container);
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ---------- Top bar ---------- */
        .topbar {
            background: var(--teal-900);
            color: #d7fbf4;
            font-size: 12.5px;
            padding: 8px 0;
        }
        .topbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .topbar strong { color: #fde68a; }

        /* ---------- Navbar ---------- */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, .96);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--slate-200);
        }
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 12px 0;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            flex-shrink: 0;
        }
        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--teal-700), var(--teal-600));
            display: grid;
            place-items: center;
            box-shadow: 0 8px 18px -6px rgba(13, 148, 136, .6);
        }
        .brand-mark svg { width: 22px; height: 22px; }
        .brand-text h1 {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -.02em;
            line-height: 1.15;
            color: var(--teal-900);
        }
        .brand-text span {
            font-size: 11px;
            font-weight: 600;
            color: var(--slate-500);
            letter-spacing: .04em;
            text-transform: uppercase;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
        }
        .nav-links a {
            display: inline-block;
            text-decoration: none;
            color: var(--slate-700);
            font-size: 13.5px;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 9px;
            transition: background .18s, color .18s;
        }
        .nav-links a:hover { background: var(--slate-100); color: var(--teal-700); }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 6px 5px 12px;
            border: 1px solid var(--slate-200);
            border-radius: 12px;
            background: var(--white);
            box-shadow: var(--shadow-sm);
        }
        .user-pill .meta { display: flex; flex-direction: column; line-height: 1.2; }
        .user-pill .meta strong { font-size: 12.5px; color: var(--slate-900); white-space: nowrap; }
        .user-pill .meta small { font-size: 10.5px; color: var(--slate-500); text-transform: uppercase; letter-spacing: .06em; white-space: nowrap; }

        .nav-mobile-only { display: none; }

        /* ---------- Buttons ---------- */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 1px solid transparent;
            border-radius: 11px;
            padding: 10px 18px;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: transform .15s, background .18s, box-shadow .18s, border-color .18s;
            white-space: nowrap;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary {
            background: var(--teal-700);
            color: var(--white);
            box-shadow: 0 10px 22px -10px rgba(15, 118, 110, .8);
        }
        .btn-primary:hover { background: var(--teal-800); }
        .btn-amber {
            background: var(--amber-500);
            color: #422006;
            box-shadow: 0 10px 22px -10px rgba(245, 158, 11, .8);
        }
        .btn-amber:hover { background: #eab308; }
        .btn-outline {
            background: transparent;
            border-color: var(--slate-300);
            color: var(--slate-700);
        }
        .btn-outline:hover { background: var(--slate-100); border-color: var(--slate-300); }
        .btn-light {
            background: rgba(255, 255, 255, .14);
            border-color: rgba(255, 255, 255, .35);
            color: var(--white);
        }
        .btn-light:hover { background: rgba(255, 255, 255, .24); }
        .btn-danger {
            background: transparent;
            color: var(--danger);
            border-color: transparent;
            padding: 8px 10px;
        }
        .btn-danger:hover { background: #fee2e2; }
        .btn-sm { padding: 7px 12px; font-size: 12.5px; border-radius: 9px; }
        .btn-block { width: 100%; }

        /* ---------- Status badge ---------- */
        .status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid transparent;
            white-space: nowrap;
        }
        .status::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }
        .status-belum_bayar { background: var(--slate-100); color: var(--slate-500); border-color: var(--slate-200); }
        .status-menunggu { background: var(--amber-100); color: #92400e; border-color: #fde68a; }
        .status-valid { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
        .status-ditolak { background: #fee2e2; color: #991b1b; border-color: #fecaca; }

        /* ---------- Flash ---------- */
        .flash {
            max-width: var(--container);
            margin: 16px auto 0;
            padding: 12px 18px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 600;
            display: flex;
            gap: 10px;
            align-items: center;
            border: 1px solid transparent;
        }
        .flash-success { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
        .flash-error { background: #fee2e2; color: #991b1b; border-color: #fecaca; }
        .flash-info { background: #e0f2fe; color: #075985; border-color: #bae6fd; }

        /* ---------- Page shell ---------- */
        main { flex: 1; }
        .page-section { padding: 56px 0; }
        .section-head { max-width: 720px; margin-bottom: 32px; }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--teal-700);
            margin-bottom: 10px;
        }
        .eyebrow::before {
            content: "";
            width: 22px;
            height: 3px;
            border-radius: 3px;
            background: var(--amber-500);
        }
        h2.section-title {
            font-size: clamp(24px, 3vw, 34px);
            line-height: 1.2;
            letter-spacing: -.025em;
            color: var(--slate-900);
        }
        p.section-desc { margin-top: 10px; color: var(--slate-500); font-size: 14.5px; }

        /* ---------- Footer ---------- */
        .footer {
            background: #08211f;
            color: #9fb8b4;
            padding: 52px 0 22px;
            margin-top: 64px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr 1.2fr;
            gap: 40px;
            padding-bottom: 34px;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }
        .footer h4 {
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 14px;
        }
        .footer p, .footer li { font-size: 13px; line-height: 1.8; }
        .footer ul { list-style: none; }
        .footer a { text-decoration: none; color: #9fb8b4; }
        .footer a:hover { color: #5eead4; }
        .footer-bottom {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            padding-top: 20px;
            font-size: 12px;
            color: #6b8f8a;
        }

        .mobile-toggle {
            display: none;
            border: 1px solid var(--slate-200);
            background: var(--white);
            border-radius: 9px;
            padding: 9px 12px;
            font-size: 16px;
            cursor: pointer;
            color: var(--slate-700);
        }

        @media (max-width: 960px) {
            .nav-links { display: none; }
            .mobile-toggle { display: inline-block; }
            .nav-links.open {
                display: flex;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                flex-direction: column;
                align-items: stretch;
                background: var(--white);
                padding: 12px 24px 18px;
                border-bottom: 1px solid var(--slate-200);
                box-shadow: var(--shadow);
            }
            .nav-links.open a { padding: 11px 12px; }
            .nav-mobile-only { display: list-item; }
            .nav-actions .nav-dashboard-link { display: none; }
        }

        @media (max-width: 720px) {
            .topbar .container { justify-content: flex-start; gap: 4px; }
            .topbar { font-size: 11.5px; }
            .navbar { gap: 10px; padding: 10px 0; }
            .brand-mark { width: 38px; height: 38px; }
            .brand-text h1 { font-size: 15px; }
            .brand-text span { font-size: 10px; }
            .nav-actions { gap: 6px; }
            .user-pill { padding: 4px; gap: 0; border-radius: 10px; }
            .user-pill .meta { display: none; }
            .footer-grid { grid-template-columns: 1fr; gap: 28px; }
            .page-section { padding: 40px 0; }
            .flash { margin: 12px 16px 0; padding: 11px 14px; font-size: 12.5px; }
        }

        @media (max-width: 420px) {
            .container { padding: 0 16px; }
            .brand-text span { display: none; }
            .brand-text h1 { font-size: 14px; letter-spacing: -.01em; }
            .brand-mark { width: 34px; height: 34px; border-radius: 10px; }
            .brand-mark svg { width: 18px; height: 18px; }
            .nav-actions .btn-sm { padding: 7px 10px; font-size: 12px; }
            .nav-links.open { padding-left: 16px; padding-right: 16px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="topbar">
        <div class="container">
            <span>Melayani perjalanan wisata, study tour, KKL, dan ziarah sejak <strong>2016</strong></span>
            <span>Metro, Lampung &middot; <strong>0812-3456-7890</strong></span>
        </div>
    </div>

    <header class="site-header">
        <div class="container navbar">
            <a href="{{ route('home') }}" class="brand">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2.5 19.5 21 12 2.5 4.5l3 7.5-3 7.5Z"/>
                        <path d="M5.5 12H21"/>
                    </svg>
                </span>
                <span class="brand-text">
                    <h1>PUMA STAR JAYA</h1>
                    <span>Tour &amp; Travel</span>
                </span>
            </a>

            <ul class="nav-links" id="navLinks">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('paket.index') }}">Paket Wisata</a></li>
                @auth
                    <li class="nav-mobile-only"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    @if (auth()->user()->isKonsumen())
                        <li><a href="{{ route('konsumen.pemesanan.index') }}">Pesanan Saya</a></li>
                    @endif
                @endauth
                <li><a href="{{ route('home') }}#tentang">Tentang Kami</a></li>
                <li><a href="{{ route('home') }}#galeri">Galeri</a></li>
                <li><a href="{{ route('home') }}#kontak">Kontak</a></li>
            </ul>

            <div class="nav-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-outline btn-sm nav-dashboard-link">Dashboard</a>
                    <div class="user-pill">
                        <div class="meta">
                            <strong>{{ auth()->user()->nama }}</strong>
                            <small>{{ auth()->user()->labelLevel() }}</small>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Keluar</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                @endauth
                <button class="mobile-toggle" id="mobileToggle" type="button" aria-label="Buka menu">&#9776;</button>
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="flash flash-success"><span>&#10003;</span><span>{{ session('success') }}</span></div>
    @endif
    @if (session('error'))
        <div class="flash flash-error"><span>!</span><span>{{ session('error') }}</span></div>
    @endif
    @if (session('info'))
        <div class="flash flash-info"><span>i</span><span>{{ session('info') }}</span></div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h4>PT. Puma Star Jaya</h4>
                    <p>Biro jasa pelayanan wisata tour &amp; travel yang melayani paket wisata nusantara, kunjungan kerja, kunjungan industri, KKL, study tour, study banding, serta ziarah Wali Songo.</p>
                </div>
                <div>
                    <h4>Navigasi</h4>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('paket.index') }}">Paket Wisata</a></li>
                        <li><a href="{{ route('login') }}">Masuk</a></li>
                        <li><a href="{{ route('register') }}">Daftar Akun</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Hubungi Kami</h4>
                    <p>
                        Jl. Sultan Dumas No. A8&ndash;A9<br>
                        Metro Selatan, Kota Metro, Lampung<br>
                        Telepon: 0812-3456-7890<br>
                        Email: info@pumastarjaya.test
                    </p>
                </div>
            </div>
            <div class="footer-bottom">
                <span>&copy; {{ date('Y') }} PT. Puma Star Jaya. Semua hak dilindungi.</span>
                <span>Sistem Informasi Pemesanan Paket Wisata Berbasis Website</span>
            </div>
        </div>
    </footer>

    <script>
        const toggle = document.getElementById('mobileToggle');
        const navLinks = document.getElementById('navLinks');
        if (toggle && navLinks) {
            toggle.addEventListener('click', () => navLinks.classList.toggle('open'));
            navLinks.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => navLinks.classList.remove('open'));
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
