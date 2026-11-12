<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') &mdash; PT. Puma Star Jaya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @include('layouts.panel-style')
</head>
<body>
    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <span class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2.5 19.5 21 12 2.5 4.5l3 7.5-3 7.5Z"/>
                    <path d="M5.5 12H21"/>
                </svg>
            </span>
            <span>
                <strong>PUMA STAR JAYA</strong>
                <small>Panel Admin</small>
            </span>
        </a>

        <div class="sidebar-section">
            <div class="sidebar-label">Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="dot"></span> Dashboard
            </a>
            <a href="{{ route('admin.paket.index') }}" class="nav-item {{ request()->routeIs('admin.paket.*') ? 'active' : '' }}">
                <span class="dot"></span> Paket Wisata
            </a>
            <a href="{{ route('admin.user.index') }}" class="nav-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                <span class="dot"></span> Manajemen User
            </a>
            <a href="{{ route('admin.pemesanan.index') }}" class="nav-item {{ request()->routeIs('admin.pemesanan.*') ? 'active' : '' }}">
                <span class="dot"></span> Data Pemesanan
            </a>
            <a href="{{ route('laporan.pemesanan') }}" class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <span class="dot"></span> Laporan
            </a>
        </div>

        <div class="sidebar-foot">
            &copy; {{ date('Y') }} PT. Puma Star Jaya<br>
            Sistem Pemesanan Paket Wisata
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <div>
                <h1>@yield('title', 'Dashboard')</h1>
                <div class="crumb">@yield('breadcrumb', 'Panel Admin')</div>
            </div>
            <div class="topbar-actions">
                <a href="{{ route('home') }}" class="btn btn-outline btn-sm" target="_blank" rel="noopener">Lihat Situs</a>
                <div class="user-chip">
                    <div class="meta">
                        <strong>{{ auth()->user()->nama }}</strong>
                        <small>{{ auth()->user()->level }}</small>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Keluar</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="content">
            @if (session('success'))
                <div class="flash flash-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="flash flash-error">{{ session('error') }}</div>
            @endif
            @if (session('info'))
                <div class="flash flash-info">{{ session('info') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
