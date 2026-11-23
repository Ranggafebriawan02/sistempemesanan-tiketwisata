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
            --traveloka-blue: #0194f3;
            --traveloka-blue-dark: #007ce8;
            --traveloka-blue-light: #e7f5ff;
            --traveloka-blue-subtle: #f2f9ff;
            --traveloka-navy: #031226;
            --traveloka-orange: #ff5e1f;
            --traveloka-orange-hover: #e04b12;
            --teal-900: #0194f3;
            --teal-800: #007ce8;
            --teal-700: #0194f3;
            --teal-600: #00a5ff;
            --teal-100: #e7f5ff;
            --teal-50: #f2f9ff;
            --amber-500: #ff5e1f;
            --amber-100: #ffede6;
            --slate-900: #1c2430;
            --slate-700: #434d5b;
            --slate-500: #687176;
            --slate-300: #cbd5e1;
            --slate-200: #e5e9ed;
            --slate-100: #f2f4f7;
            --slate-50: #f7f9fa;
            --white: #ffffff;
            --danger: #d32f2f;
            --success: #05a569;
            --radius: 12px;
            --shadow-sm: 0 1px 3px rgba(3, 18, 38, .07);
            --shadow: 0 4px 16px rgba(3, 18, 38, .08);
            --shadow-lg: 0 12px 32px rgba(3, 18, 38, .12);
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
            background: #0264b1;
            color: #dff0ff;
            font-size: 12.5px;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }
        .topbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .topbar strong { color: #ffffff; font-weight: 700; }

        /* ---------- Navbar ---------- */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: #ffffff;
            border-bottom: 1px solid var(--slate-200);
            box-shadow: 0 2px 8px rgba(3, 18, 38, 0.04);
        }
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 14px 0;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            flex-shrink: 0;
        }
        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #0194f3, #007ce8);
            display: grid;
            place-items: center;
            box-shadow: 0 4px 12px rgba(1, 148, 243, 0.35);
        }
        .brand-mark svg { width: 22px; height: 22px; }
        .brand-text h1 {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -.02em;
            line-height: 1.15;
            color: #0194f3;
        }
        .brand-text span {
            font-size: 11px;
            font-weight: 700;
            color: var(--slate-500);
            letter-spacing: .05em;
            text-transform: uppercase;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
        }
        .nav-links a {
            display: inline-block;
            text-decoration: none;
            color: var(--slate-700);
            font-size: 14px;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 8px;
            transition: background .15s, color .15s;
        }
        .nav-links a:hover { background: var(--traveloka-blue-light); color: var(--traveloka-blue); }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 8px 6px 14px;
            border: 1px solid var(--slate-200);
            border-radius: 999px;
            background: var(--white);
            box-shadow: var(--shadow-sm);
        }
        .user-pill .meta { display: flex; flex-direction: column; line-height: 1.2; }
        .user-pill .meta strong { font-size: 12.5px; color: var(--slate-900); white-space: nowrap; }
        .user-pill .meta small { font-size: 10px; color: var(--traveloka-blue); font-weight: 700; text-transform: uppercase; letter-spacing: .06em; white-space: nowrap; }

        .nav-mobile-only { display: none; }

        /* ---------- Buttons (Traveloka Style) ---------- */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 1px solid transparent;
            border-radius: 8px;
            padding: 10px 20px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all .18s ease-in-out;
            white-space: nowrap;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary {
            background: var(--traveloka-blue);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(1, 148, 243, 0.35);
        }
        .btn-primary:hover { background: var(--traveloka-blue-dark); color: #fff; }
        .btn-amber {
            background: var(--traveloka-orange);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(255, 94, 31, 0.4);
        }
        .btn-amber:hover { background: var(--traveloka-orange-hover); color: #fff; }
        .btn-outline {
            background: #ffffff;
            border-color: var(--traveloka-blue);
            color: var(--traveloka-blue);
            font-weight: 700;
        }
        .btn-outline:hover { background: var(--traveloka-blue-light); border-color: var(--traveloka-blue); }
        .btn-light {
            background: #ffffff;
            border-color: #ffffff;
            color: var(--traveloka-blue);
            font-weight: 700;
        }
        .btn-light:hover { background: #f0f7ff; color: var(--traveloka-blue-dark); }
        .btn-danger {
            background: transparent;
            color: var(--danger);
            border-color: transparent;
            padding: 8px 10px;
        }
        .btn-danger:hover { background: #fee2e2; }
        .btn-sm { padding: 8px 14px; font-size: 13px; border-radius: 8px; }
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
            background: #031226;
            color: #93a3b8;
            padding: 56px 0 24px;
            margin-top: 64px;
            border-top: 3px solid var(--traveloka-blue);
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
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .footer p, .footer li { font-size: 13.5px; line-height: 1.8; }
        .footer ul { list-style: none; }
        .footer a { text-decoration: none; color: #93a3b8; transition: color .15s; }
        .footer a:hover { color: #0194f3; }
        .footer-bottom {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            padding-top: 20px;
            font-size: 12px;
            color: #64748b;
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
