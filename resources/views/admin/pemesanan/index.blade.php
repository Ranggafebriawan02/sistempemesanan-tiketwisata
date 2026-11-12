@extends('layouts.admin')

@section('title', 'Data Pemesanan')
@section('breadcrumb', 'Admin / Data Pemesanan')

@push('styles')
<style>
    .filter-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .filter-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        color: var(--slate-600);
        background: var(--slate-100);
        border: 1px solid var(--slate-200);
        transition: all 0.2s;
    }
    .filter-tab:hover {
        background: var(--slate-200);
        color: var(--slate-900);
    }
    .filter-tab.active {
        background: var(--teal-700);
        color: #fff;
        border-color: var(--teal-700);
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.25);
    }
    .filter-tab .count {
        background: rgba(0, 0, 0, 0.08);
        padding: 2px 7px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }
    .filter-tab.active .count {
        background: rgba(255, 255, 255, 0.25);
        color: #fff;
    }

    .search-box {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .search-box input {
        border: 1px solid var(--slate-300);
        border-radius: 9px;
        padding: 8px 14px;
        font-size: 13px;
        min-width: 250px;
        outline: none;
        transition: border-color 0.2s;
    }
    .search-box input:focus {
        border-color: var(--teal-600);
    }

    /* Status Pill Badges */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .02em;
        white-space: nowrap;
    }
    .status-pill.valid { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .status-pill.menunggu { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .status-pill.ditolak { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
    .status-pill.belum_bayar { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }

    .table-order {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .table-order th {
        background: var(--slate-50);
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--slate-500);
        border-bottom: 1px solid var(--slate-200);
        white-space: nowrap;
    }
    .table-order td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--slate-100);
        vertical-align: middle;
    }
    .table-order tr:hover td {
        background: #f8fafc;
    }
</style>
@endpush

@section('content')
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-body" style="display:flex; gap:16px; justify-content:space-between; align-items:center; flex-wrap:wrap; padding: 16px 20px;">
            <div class="filter-tabs">
                @php
                    $tabs = [
                        'semua' => 'Semua',
                        'menunggu' => 'Menunggu Validasi',
                        'valid' => 'Valid',
                        'ditolak' => 'Ditolak',
                        'belum_bayar' => 'Belum Bayar',
                    ];
                @endphp
                @foreach ($tabs as $key => $label)
                    <a href="{{ route('admin.pemesanan.index', array_filter(['status' => $key, 'q' => $q])) }}"
                       class="filter-tab {{ $status === $key ? 'active' : '' }}">
                        {{ $label }}
                        <span class="count">{{ $counts[$key] }}</span>
                    </a>
                @endforeach
            </div>

            <form action="{{ route('admin.pemesanan.index') }}" method="GET" class="search-box">
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari kode, nama, atau paket...">
                <button type="submit" class="btn btn-primary btn-sm">Cari</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <div>
                <h2>Daftar Pemesanan Wisata</h2>
                <p>
                    @if ($status === 'semua')
                        Menampilkan seluruh riwayat pesanan tiket dari seluruh konsumen.
                    @elseif ($status === 'belum_bayar')
                        Menampilkan pesanan yang masih menunggu pembayaran dari konsumen.
                    @else
                        Menampilkan pesanan berstatus <strong>{{ strtoupper($status) }}</strong>.
                    @endif
                </p>
            </div>
            <span class="badge">{{ $pemesanan->total() }} DATA</span>
        </div>

        <div class="table-wrap">
            <table class="table-order">
                <thead>
                    <tr>
                        <th style="width: 140px;">Kode Pesanan</th>
                        <th style="width: 160px;">Konsumen</th>
                        <th>Paket Wisata &amp; Rombongan</th>
                        <th style="width: 110px;">Berangkat</th>
                        <th style="width: 80px; text-align: center;">Peserta</th>
                        <th style="width: 130px;">Total Tagihan</th>
                        <th style="width: 130px; text-align: center;">Status</th>
                        <th style="width: 90px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pemesanan as $p)
                        @php $st = $p->statusPembayaran(); @endphp
                        <tr>
                            <td>
                                <strong style="color: var(--teal-800); font-size: 13px;">{{ $p->kode_pemesanan }}</strong>
                                <small style="display:block; color:var(--slate-400); font-size: 11px; margin-top: 3px;">
                                    {{ $p->created_at->translatedFormat('d M Y, H:i') }}
                                </small>
                            </td>
                            <td>
                                <strong style="display:block; color:var(--slate-800);">{{ $p->user->nama ?? '-' }}</strong>
                                <small style="display:block; color:var(--slate-500); font-size: 11.5px; margin-top: 2px;">
                                    📞 {{ $p->no_hp }}
                                </small>
                            </td>
                            <td>
                                <strong style="display:block; color:var(--slate-900);">{{ $p->paket->judul ?? '-' }}</strong>
                                <small style="display:block; color:var(--slate-500); font-size: 11.5px; margin-top: 3px;">
                                    📍 {{ $p->nama_rombongan ? 'Rombongan: '.$p->nama_rombongan : ($p->paket->destinasi ?? '-') }}
                                </small>
                            </td>
                            <td style="white-space: nowrap;">
                                {{ $p->tanggal_berangkat->translatedFormat('d M Y') }}
                            </td>
                            <td style="text-align: center; white-space: nowrap;">
                                <span style="background: var(--slate-100); padding: 3px 8px; border-radius: 6px; font-weight: 700; font-size: 12px;">
                                    {{ $p->jumlah_pax }} orang
                                </span>
                            </td>
                            <td style="white-space: nowrap;">
                                <strong style="color: var(--teal-900); font-size: 13.5px;">
                                    Rp {{ number_format((float) $p->harga, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td style="text-align: center;">
                                <span class="status-pill {{ $st }}">
                                    @if($st === 'valid')
                                        ✔ Pembayaran Valid
                                    @elseif($st === 'menunggu')
                                        ⏳ Menunggu Validasi
                                    @elseif($st === 'ditolak')
                                        ✖ Ditolak
                                    @else
                                        ⌛ Belum Bayar
                                    @endif
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('admin.pemesanan.show', $p) }}" class="btn btn-outline btn-sm" style="padding: 5px 12px; font-weight: 700;">
                                    Detail &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty" style="padding: 40px; text-align: center; color: var(--slate-400);">
                                Tidak ada data pemesanan yang cocok dengan filter atau pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pemesanan->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--slate-200);">
                {{ $pemesanan->links() }}
            </div>
        @endif
    </div>
@endsection
