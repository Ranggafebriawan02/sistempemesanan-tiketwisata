@extends('layouts.app')

@section('title', 'Dashboard — PT. Puma Star Jaya')

@push('styles')
<style>
    .dash-hero {
        background: linear-gradient(135deg, #042f2e 0%, #0d544c 50%, #115e59 100%);
        color: #fff;
        padding: 36px 0 40px;
    }
    .dash-hero-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .dash-hero h1 {
        font-size: clamp(22px, 3vw, 30px);
        font-weight: 800;
        letter-spacing: -.02em;
        margin-bottom: 6px;
    }
    .dash-hero p {
        font-size: 14px;
        color: #ccfbf1;
        max-width: 600px;
        line-height: 1.5;
        margin: 0;
    }
    .badge-role {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .05em;
        color: #fef08a;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
        border: 1px solid var(--slate-200);
        border-radius: 16px;
        padding: 20px 22px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .icon-emerald { background: #dcfce7; color: #15803d; }
    .icon-blue { background: #dbeafe; color: #1d4ed8; }
    .icon-amber { background: #fef3c7; color: #b45309; }
    .icon-teal { background: #ccfbf1; color: #0f766e; }

    .stat-data { min-width: 0; }
    .stat-data .num {
        font-size: clamp(18px, 1.8vw, 22px);
        font-weight: 800;
        color: var(--slate-900);
        line-height: 1.2;
        overflow-wrap: anywhere;
    }
    .stat-data .lbl {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--slate-500);
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
        border: 1px solid var(--slate-200);
        border-radius: 16px;
        padding: 24px;
        box-shadow: var(--shadow-sm);
    }
    .box-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--slate-900);
        letter-spacing: -.01em;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
    }
    .box-desc {
        font-size: 13px;
        color: var(--slate-500);
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
        padding: 18px;
        border-radius: 14px;
        border: 1.5px solid var(--slate-200);
        text-decoration: none;
        color: inherit;
        background: #fff;
        transition: all 0.2s ease;
    }
    .menu-card:hover {
        border-color: var(--teal-600);
        background: #f0fdfa;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(13, 148, 136, 0.12);
    }
    .menu-card-top {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }
    .menu-emoji {
        font-size: 24px;
        background: var(--slate-100);
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .menu-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--slate-800);
    }
    .menu-desc {
        font-size: 12px;
        color: var(--slate-500);
        line-height: 1.45;
        margin-bottom: 12px;
    }
    .menu-action {
        font-size: 12px;
        font-weight: 700;
        color: var(--teal-700);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Profile / Account Card */
    .profile-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: var(--slate-50);
        border: 1px solid var(--slate-200);
        border-radius: 14px;
        margin-bottom: 20px;
    }
    .profile-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--teal-700), var(--teal-900));
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
        color: var(--slate-900);
    }
    .profile-info span {
        font-size: 12.5px;
        color: var(--slate-500);
    }

    .detail-table {
        width: 100%;
        border-collapse: collapse;
    }
    .detail-table tr {
        border-bottom: 1px solid var(--slate-100);
    }
    .detail-table tr:last-child {
        border-bottom: none;
    }
    .detail-table td {
        padding: 10px 0;
        font-size: 13px;
    }
    .detail-table td:first-child {
        color: var(--slate-500);
        width: 38%;
    }
    .detail-table td:last-child {
        color: var(--slate-900);
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
