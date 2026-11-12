@extends('layouts.bendahara')

@section('title', 'Manajemen Pemesanan')
@section('breadcrumb', 'Bendahara / Manajemen Pemesanan')

@section('content')
    <div class="card" style="margin-bottom: 18px;">
        <div class="card-body" style="display:flex; gap:16px; justify-content:space-between; align-items:center; flex-wrap:wrap;">
            <div class="filter-tabs">
                @php
                    $tabs = [
                        'semua' => 'Semua',
                        'menunggu' => 'Menunggu',
                        'valid' => 'Valid',
                        'ditolak' => 'Ditolak',
                        'belum_bayar' => 'Belum Bayar',
                    ];
                @endphp
                @foreach ($tabs as $key => $label)
                    <a href="{{ route('bendahara.pemesanan.index', array_filter(['status' => $key, 'q' => $q])) }}"
                       class="filter-tab {{ $status === $key ? 'active' : '' }}">
                        {{ $label }}
                        <span class="count">{{ $counts[$key] }}</span>
                    </a>
                @endforeach
            </div>

            <form action="{{ route('bendahara.pemesanan.index') }}" method="GET" class="search-form">
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari kode, nama, atau paket...">
                <button type="submit" class="btn btn-outline btn-sm">Cari</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <div>
                <h2>Daftar Pemesanan</h2>
                <p>
                    @if ($status === 'semua')
                        Menampilkan seluruh pemesanan.
                    @elseif ($status === 'belum_bayar')
                        Pemesanan yang belum memiliki bukti pembayaran.
                    @else
                        Pemesanan dengan status pembayaran <strong>{{ $status }}</strong>.
                    @endif
                </p>
            </div>
            <span class="badge">{{ $pemesanan->total() }} data</span>
        </div>

        @if ($pemesanan->isEmpty())
            <div class="empty">
                Tidak ada data pemesanan yang sesuai dengan filter.
            </div>
        @else
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>Kode / Pemesan</th>
                            <th>Paket</th>
                            <th>Keberangkatan</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                            <th style="width:90px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pemesanan as $item)
                            @php $st = $item->statusPembayaran(); @endphp
                            <tr>
                                <td>
                                    <div class="cell-title">{{ $item->kode_pemesanan }}</div>
                                    <div class="cell-sub">{{ $item->user->nama ?? '-' }} &middot; {{ $item->no_hp }}</div>
                                </td>
                                <td>
                                    <div class="cell-title" style="font-weight:600;">{{ $item->paket->judul ?? '-' }}</div>
                                    <div class="cell-sub">{{ $item->jumlah_pax }} peserta</div>
                                </td>
                                <td>{{ $item->tanggal_berangkat->translatedFormat('d M Y') }}</td>
                                <td class="money">Rp {{ number_format((float) $item->harga, 0, ',', '.') }}</td>
                                <td><span class="status status-{{ $st }}">{{ $item->labelStatus() }}</span></td>
                                <td>
                                    <a href="{{ route('bendahara.pemesanan.show', $item) }}" class="btn btn-outline btn-sm">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-body" style="padding-top:0;">
                {{ $pemesanan->links('pagination.simple') }}
            </div>
        @endif
    </div>
@endsection
