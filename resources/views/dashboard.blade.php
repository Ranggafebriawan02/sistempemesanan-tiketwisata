@extends('layouts.app')

@section('title', 'Dashboard — PT. Puma Star Jaya')

@push('styles')
<style>
    .dash-hero {
        background: #ffffff;
        color: #222222;
        padding: 40px 0 28px;
        border-bottom: 1px solid #ebebeb;
    }
    .dash-hero-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .dash-hero h1 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(26px, 3.2vw, 36px);
        font-weight: 800;
        letter-spacing: -.02em;
        margin-bottom: 6px;
        color: #222222;
    }
    .dash-hero p {
        font-size: 15px;
        color: #717171;
        max-width: 620px;
        line-height: 1.5;
        margin: 0;
    }
    .badge-role {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f7f7f7;
        border: 1px solid #dddddd;
        padding: 8px 18px;
        border-radius: var(--radius-full);
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .04em;
        color: #222222;
    }

    .main-wrap {
        position: relative;
        z-index: 5;
        margin-top: 32px;
        padding-bottom: 60px;
    }

    /* Stats Grid Airbnb Style */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }
    .stat-card {
        background: #ffffff;
        border: 1px solid #ebebeb;
        border-radius: 18px;
        padding: 24px;
        box-shadow: var(--shadow-subtle);
        display: flex;
        align-items: center;
        gap: 18px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-card);
    }
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
        background: #f7f7f7;
        border: 1px solid #ebebeb;
    }
    .icon-emerald { color: #008a05; }
    .icon-blue { color: #0066cc; }
    .icon-amber { color: #d97706; }
    .icon-teal { color: #ff385c; }

    .stat-data { min-width: 0; }
    .stat-data .num {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(22px, 2.2vw, 26px);
        font-weight: 800;
        color: #222222;
        line-height: 1.15;
    }
    .stat-data .lbl {
        font-size: 13px;
        font-weight: 600;
        color: #717171;
        margin-top: 4px;
    }

    /* 2 Columns Layout */
    .content-grid {
        display: grid;
        grid-template-columns: 1.55fr 1fr;
        gap: 28px;
        align-items: start;
    }

    .box {
        background: #ffffff;
        border: 1px solid #ebebeb;
        border-radius: 20px;
        padding: 28px;
        box-shadow: var(--shadow-subtle);
    }
    .box-title {
        font-family: 'Outfit', sans-serif;
        font-size: 19px;
        font-weight: 800;
        color: #222222;
        letter-spacing: -.01em;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
    }
    .box-desc {
        font-size: 13.5px;
        color: #717171;
        margin-bottom: 22px;
    }

    /* Menu Cards */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    .menu-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 20px;
        border-radius: 16px;
        border: 1.5px solid #ebebeb;
        text-decoration: none;
        color: inherit;
        background: #ffffff;
        transition: all 0.2s ease;
    }
    .menu-card:hover {
        border-color: #222222;
        transform: translateY(-3px);
        box-shadow: var(--shadow-card);
    }
    .menu-card-top {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 12px;
    }
    .menu-emoji {
        font-size: 24px;
        background: #f7f7f7;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .menu-title {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 800;
        color: #222222;
    }
    .menu-desc {
        font-size: 13px;
        color: #717171;
        line-height: 1.5;
        margin-bottom: 14px;
    }
    .menu-action {
        font-size: 13px;
        font-weight: 700;
        color: #ff385c;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .main-wrap {
        position: relative;
        z-index: 5;
        margin-top: 24px;
        padding-bottom: 60px;
    }

    /* Stats Grid */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: #fff;
        border: 1px solid #e5e9ed;
        border-radius: 16px;
        padding: 22px 24px;
        box-shadow: 0 4px 16px rgba(3, 18, 38, 0.04);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(3, 18, 38, 0.08);
        border-color: #b9e3ff;
    }
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .icon-emerald { background: #e6f7ef; color: #05a569; }
    .icon-blue { background: #e7f5ff; color: #0194f3; }
    .icon-amber { background: #fff4eb; color: #ff5e1f; }
    .icon-teal { background: #eff6ff; color: #2563eb; }

    .stat-data { min-width: 0; }
    .stat-data .num {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(20px, 2vw, 24px);
        font-weight: 900;
        color: #1c2430;
        line-height: 1.2;
        overflow-wrap: anywhere;
    }
    .stat-data .lbl {
        font-size: 12.5px;
        font-weight: 700;
        color: #687176;
        margin-top: 4px;
    }

    /* 2 Columns Layout */
    .content-grid {
        display: grid;
        grid-template-columns: 1.55fr 1fr;
        gap: 24px;
        align-items: start;
    }

    .box {
        background: #fff;
        border: 1px solid #ebebeb;
        border-radius: 16px;
        padding: 24px;
        box-shadow: var(--shadow-sm);
    }
    .box-title {
        font-size: 17px;
        font-weight: 800;
        color: #222222;
        letter-spacing: -.01em;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
    }
    .box-desc {
        font-size: 13px;
        color: #717171;
        margin-bottom: 20px;
    }

    /* Menu Cards */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }
    .menu-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 20px;
        border-radius: 14px;
        border: 1.5px solid #e5e9ed;
        text-decoration: none;
        color: inherit;
        background: #fff;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(3, 18, 38, 0.03);
    }
    .menu-card:hover {
        border-color: #0194f3;
        background: #f0f7ff;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(1, 148, 243, 0.12);
    }
    .menu-card-top {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 12px;
    }
    .menu-emoji {
        font-size: 24px;
        background: #e7f5ff;
        color: #0194f3;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .menu-title {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 800;
        color: #1c2430;
    }
    .menu-desc {
        font-size: 12.5px;
        color: #687176;
        line-height: 1.5;
        margin-bottom: 14px;
    }
    .menu-action {
        font-size: 13px;
        font-weight: 800;
        color: #0194f3;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Profile / Account Card */
    .profile-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #f7f7f7;
        border: 1px solid #ebebeb;
        border-radius: 14px;
        margin-bottom: 20px;
    }
    .profile-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, #222222, #000000);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 800;
        flex-shrink: 0;
    }
    .profile-info strong {
        display: block;
        font-size: 15px;
        color: #222222;
    }
    .profile-info span {
        font-size: 12.5px;
        color: #717171;
    }

    .detail-table {
        width: 100%;
        border-collapse: collapse;
    }
    .detail-table tr {
        border-bottom: 1px solid #f0f0f0;
    }
    .detail-table tr:last-child {
        border-bottom: none;
    }
    .detail-table td {
        padding: 10px 0;
        font-size: 13px;
    }
    .detail-table td:first-child {
        color: #717171;
        width: 38%;
    }
    .detail-table td:last-child {
        color: #222222;
        font-weight: 700;
        text-align: right;
    }

    .info-banner {
        margin-top: 20px;
        background: #f0fdfa;
        border: 1px solid #99f6e4;
        border-radius: 12px;
        padding: 14px 16px;
        font-size: 12.5px;
        color: #0f766e;
        line-height: 1.5;
    }

    @media (max-width: 980px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
        .content-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 580px) {
        .stats-row { grid-template-columns: 1fr; }
        .menu-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
    @php
        $roleInfo = match ($user->level) {
            'admin' => ['label' => 'Staff Administrator', 'desc' => 'Panel kendali utama pengelolaan paket wisata, data user, dan monitoring transaksi.'],
            'bendahara' => ['label' => 'Staff Bendahara', 'desc' => 'Validasi bukti pembayaran konsumen, kuitansi, dan penyusunan laporan keuangan.'],
            'direktur' => ['label' => 'Direktur Perusahaan', 'desc' => 'Pemantauan berkala laporan perkembangan pemesanan dan rekap pendapatan usaha.'],
            default => ['label' => 'Akun Konsumen', 'desc' => 'Temukan paket liburan impian, buat pemesanan tiket, dan bayar instan via Midtrans.'],
        };

        $icons = ['icon-teal', 'icon-blue', 'icon-amber', 'icon-emerald'];
        $emojis = ['📊', '📋', '👥', '💰'];
        $i = 0;
    @endphp

    <section class="dash-hero">
        <div class="container dash-hero-inner">
            <div>
                <h1>Halo, {{ $user->nama }}! 👋</h1>
                <p>{{ $roleInfo['desc'] }}</p>
            </div>
            <div class="badge-role">
                <span>🛡️</span> {{ $roleInfo['label'] }}
            </div>
        </div>
    </section>

    <div class="container main-wrap">
        {{-- Ringkasan Statistik --}}
        <div class="stats-row">
            @foreach ($stats as $label => $value)
                @php
                    $colorClass = $icons[$i % 4];
                    $emoji = $emojis[$i % 4];
                    $i++;
                @endphp
                <div class="stat-card">
                    <div class="stat-icon {{ $colorClass }}">{{ $emoji }}</div>
                    <div class="stat-data">
                        <div class="num">{{ $value }}</div>
                        <div class="lbl">{{ $label }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Konten Utama: Menu Akses Cepat & Profil --}}
        <div class="content-grid">
            <div class="box">
                <div class="box-title">🚀 Menu Utama &amp; Akses Cepat</div>
                <div class="box-desc">Pilih menu navigasi di bawah untuk menjalankan tugas atau melihat informasi sistem.</div>

                <div class="menu-grid">
                    @if ($user->isKonsumen())
                        <a href="{{ route('paket.index') }}" class="menu-card">
                            <div>
                                <div class="menu-card-top">
                                    <div class="menu-emoji">🏖️</div>
                                    <div class="menu-title">Katalog Wisata</div>
                                </div>
                                <div class="menu-desc">Cari dan jelajahi destinasi liburan, study tour, atau ziarah terbaik.</div>
                            </div>
                            <div class="menu-action">Pilih Paket &rarr;</div>
                        </a>

                        <a href="{{ route('konsumen.pemesanan.index') }}" class="menu-card">
                            <div>
                                <div class="menu-card-top">
                                    <div class="menu-emoji">🎫</div>
                                    <div class="menu-title">Pesanan Saya</div>
                                </div>
                                <div class="menu-desc">Lihat status tiket, riwayat pesanan, dan selesaikan pembayaran.</div>
                            </div>
                            <div class="menu-action">Buka Pesanan &rarr;</div>
                        </a>
                    @endif

                    @if ($user->isAdmin())
                        <a href="{{ route('admin.paket.index') }}" class="menu-card">
                            <div>
                                <div class="menu-card-top">
                                    <div class="menu-emoji">🏖️</div>
                                    <div class="menu-title">Kelola Paket Wisata</div>
                                </div>
                                <div class="menu-desc">Tambah paket baru, ubah harga, rute, foto, dan fasilitas armada.</div>
                            </div>
                            <div class="menu-action">Kelola Paket &rarr;</div>
                        </a>

                        <a href="{{ route('admin.pemesanan.index') }}" class="menu-card">
                            <div>
                                <div class="menu-card-top">
                                    <div class="menu-emoji">📑</div>
                                    <div class="menu-title">Data Pemesanan</div>
                                </div>
                                <div class="menu-desc">Pantau seluruh order pemesanan tiket masuk dari konsumen.</div>
                            </div>
                            <div class="menu-action">Lihat Pemesanan &rarr;</div>
                        </a>

                        <a href="{{ route('admin.user.index') }}" class="menu-card">
                            <div>
                                <div class="menu-card-top">
                                    <div class="menu-emoji">👥</div>
                                    <div class="menu-title">Manajemen User</div>
                                </div>
                                <div class="menu-desc">Kelola akun staf, reset password, dan status keaktifan user.</div>
                            </div>
                            <div class="menu-action">Atur Pengguna &rarr;</div>
                        </a>

                        <a href="{{ route('laporan.pemesanan') }}" class="menu-card">
                            <div>
                                <div class="menu-card-top">
                                    <div class="menu-emoji">📈</div>
                                    <div class="menu-title">Laporan &amp; Rekap</div>
                                </div>
                                <div class="menu-desc">Cetak dan rekap data pemesanan serta transaksi per periode.</div>
                            </div>
                            <div class="menu-action">Buka Laporan &rarr;</div>
                        </a>
                    @endif

                    @if ($user->isBendahara())
                        <a href="{{ route('bendahara.pemesanan.index') }}" class="menu-card">
                            <div>
                                <div class="menu-card-top">
                                    <div class="menu-emoji">💳</div>
                                    <div class="menu-title">Validasi Pembayaran</div>
                                </div>
                                <div class="menu-desc">Verifikasi pembayaran pesanan dan cetak kuitansi resmi.</div>
                            </div>
                            <div class="menu-action">Validasi Sekarang &rarr;</div>
                        </a>

                        <a href="{{ route('laporan.pembayaran') }}" class="menu-card">
                            <div>
                                <div class="menu-card-top">
                                    <div class="menu-emoji">💵</div>
                                    <div class="menu-title">Laporan Keuangan</div>
                                </div>
                                <div class="menu-desc">Rekapitulasi kas masuk dan cetak laporan pembayaran.</div>
                            </div>
                            <div class="menu-action">Lihat Laporan &rarr;</div>
                        </a>

                        <a href="{{ route('laporan.pemesanan') }}" class="menu-card">
                            <div>
                                <div class="menu-card-top">
                                    <div class="menu-emoji">📑</div>
                                    <div class="menu-title">Laporan Pemesanan</div>
                                </div>
                                <div class="menu-desc">Rekap data seluruh pemesanan tiket wisata per periode.</div>
                            </div>
                            <div class="menu-action">Buka Rekap &rarr;</div>
                        </a>
                    @endif

                    @if ($user->isDirektur())
                        <a href="{{ route('laporan.pemesanan') }}" class="menu-card">
                            <div>
                                <div class="menu-card-top">
                                    <div class="menu-emoji">📊</div>
                                    <div class="menu-title">Laporan Pemesanan</div>
                                </div>
                                <div class="menu-desc">Monitoring statistik pemesanan paket wisata perusahaan.</div>
                            </div>
                            <div class="menu-action">Lihat Statistik &rarr;</div>
                        </a>

                        <a href="{{ route('laporan.pembayaran') }}" class="menu-card">
                            <div>
                                <div class="menu-card-top">
                                    <div class="menu-emoji">💰</div>
                                    <div class="menu-title">Laporan Pendapatan</div>
                                </div>
                                <div class="menu-desc">Monitoring omzet dan status realisasi kas masuk perusahaan.</div>
                            </div>
                            <div class="menu-action">Lihat Pendapatan &rarr;</div>
                        </a>
                    @endif
                </div>
            </div>

            <div class="box">
                <div class="box-title">👤 Informasi Akun</div>
                <div class="box-desc">Identitas login dan hak akses Anda di sistem.</div>

                <div class="profile-card">
                    <div class="profile-avatar">
                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                    </div>
                    <div class="profile-info">
                        <strong>{{ $user->nama }}</strong>
                        <span>{{ '@' . $user->username }} &bull; {{ $roleInfo['label'] }}</span>
                    </div>
                </div>

                <table class="detail-table">
                    <tr>
                        <td>Email</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td>No. Handphone</td>
                        <td>{{ $user->no_hp ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td>Status Akun</td>
                        <td><span style="color:#059669; background:#dcfce7; padding: 2px 8px; border-radius: 6px; font-size: 11px;">Aktif</span></td>
                    </tr>
                    <tr>
                        <td>Terdaftar Sejak</td>
                        <td>{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</td>
                    </tr>
                </table>

                <div class="info-banner">
                    <strong>💡 Tips Cepat:</strong><br>
                    Gunakan navigasi menu di sebelah kiri untuk mengakses fitur sesuai kewenangan tugas Anda di <strong>PT. Puma Star Jaya</strong>.
                </div>
            </div>
        </div>
    </div>
@endsection
