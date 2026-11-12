@extends(auth()->user()->isAdmin() ? 'layouts.admin' : (auth()->user()->isDirektur() ? 'layouts.direktur' : 'layouts.bendahara'))

@section('title', 'Laporan Pemesanan')
@section('breadcrumb', 'Laporan / Pemesanan')

@section('content')
    @php
        $statusLabels = [
            'semua' => 'Semua Status',
            'belum_bayar' => 'Belum Bayar',
            'menunggu' => 'Menunggu Validasi',
            'valid' => 'Pembayaran Valid',
            'ditolak' => 'Pembayaran Ditolak',
        ];
    @endphp

    @if ($errors->any())
        <div class="flash flash-error">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card" style="margin-bottom: 18px;">
        <div class="card-head">
            <div>
                <h2>Filter Laporan Pemesanan</h2>
                <p>Data dihitung berdasarkan tanggal pemesanan dibuat.</p>
            </div>
            <a href="{{ route('laporan.pemesanan.cetak', request()->only(['dari', 'sampai', 'status', 'paket_id'])) }}"
               target="_blank" rel="noopener" class="btn btn-primary btn-sm">
                Cetak / PDF
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan.pemesanan') }}" method="GET" class="report-filter">
                <div class="field">
                    <label for="dari">Dari Tanggal</label>
                    <input type="date" id="dari" name="dari" value="{{ $filter['dari'] }}">
                </div>
                <div class="field">
                    <label for="sampai">Sampai Tanggal</label>
                    <input type="date" id="sampai" name="sampai" value="{{ $filter['sampai'] }}">
                </div>
                <div class="field">
                    <label for="status">Status Pembayaran</label>
                    <select id="status" name="status">
                        @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected($filter['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="paket_id">Paket Wisata</label>
                    <select id="paket_id" name="paket_id">
                        <option value="">Semua Paket</option>
                        @foreach ($daftarPaket as $paket)
                            <option value="{{ $paket->id }}" @selected($filter['paket_id'] === $paket->id)>{{ $paket->judul }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                    <a href="{{ route('laporan.pemesanan') }}" class="btn btn-outline">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="report-stats">
        <div class="stat-card">
            <div class="label">Total Pemesanan</div>
            <div class="value">{{ number_format($ringkasan['jumlah'], 0, ',', '.') }}</div>
            <div class="sub">Transaksi pemesanan pada periode ini</div>
        </div>
        <div class="stat-card">
            <div class="label">Total Peserta</div>
            <div class="value">{{ number_format($ringkasan['pax'], 0, ',', '.') }}</div>
            <div class="sub">Orang yang dijadwalkan berangkat</div>
        </div>
        <div class="stat-card">
            <div class="label">Nilai Pemesanan</div>
            <div class="value">Rp {{ number_format($ringkasan['nilai'], 0, ',', '.') }}</div>
            <div class="sub">Total tagihan seluruh pemesanan</div>
        </div>
        <div class="stat-card">
            <div class="label">Sudah Lunas</div>
            <div class="value">{{ number_format($ringkasan['lunas_jumlah'], 0, ',', '.') }}</div>
            <div class="sub">Rp {{ number_format($ringkasan['lunas_nilai'], 0, ',', '.') }} terbayar valid</div>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <div>
                <h2>Rekap Pemesanan</h2>
                <p>Periode {{ $periode['dari'] }} &ndash; {{ $periode['sampai'] }} &middot; {{ $statusLabels[$filter['status']] ?? 'Semua Status' }}</p>
            </div>
            <span class="badge">{{ $pemesanan->total() }} data</span>
        </div>

        @if ($pemesanan->isEmpty())
            <div class="empty">Tidak ada pemesanan pada periode dan filter yang dipilih.</div>
        @else
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th style="width:44px;">No</th>
                            <th>Kode / Pemesan</th>
                            <th>Paket Wisata</th>
                            <th>Tgl. Pesan</th>
                            <th>Keberangkatan</th>
                            <th class="text-right">Pax</th>
                            <th class="text-right">Total Tagihan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pemesanan as $index => $item)
                            @php $status = $item->statusPembayaran(); @endphp
                            <tr>
                                <td>{{ $pemesanan->firstItem() + $index }}</td>
                                <td>
                                    <div class="cell-title">{{ $item->kode_pemesanan }}</div>
                                    <div class="cell-sub">{{ $item->user->nama ?? '-' }} &middot; {{ $item->no_hp }}</div>
                                </td>
                                <td>
                                    <div class="cell-title" style="font-weight:600;">{{ $item->paket->judul ?? '-' }}</div>
                                    <div class="cell-sub">{{ $item->nama_rombongan ?: 'Tanpa rombongan' }}</div>
                                </td>
                                <td>{{ $item->created_at->translatedFormat('d M Y') }}</td>
                                <td>{{ $item->tanggal_berangkat->translatedFormat('d M Y') }}</td>
                                <td class="text-right">{{ number_format($item->jumlah_pax, 0, ',', '.') }}</td>
                                <td class="text-right money">Rp {{ number_format((float) $item->harga, 0, ',', '.') }}</td>
                                <td><span class="status status-{{ $status }}">{{ $item->labelStatus() }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-right">Total halaman ini</th>
                            <th class="text-right">{{ number_format($pemesanan->sum('jumlah_pax'), 0, ',', '.') }}</th>
                            <th class="text-right money">Rp {{ number_format((float) $pemesanan->sum('harga'), 0, ',', '.') }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="card-body" style="padding-top: 0;">
                {{ $pemesanan->links('pagination.simple') }}
            </div>
        @endif
    </div>
@endsection
