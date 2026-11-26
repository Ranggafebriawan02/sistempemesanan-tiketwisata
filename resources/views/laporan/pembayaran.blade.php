@extends(auth()->user()->isAdmin() ? 'layouts.admin' : (auth()->user()->isDirektur() ? 'layouts.direktur' : 'layouts.bendahara'))

@section('title', 'Laporan Pembayaran')
@section('breadcrumb', 'Laporan / Pembayaran')

@section('content')
    @php
        $statusLabels = [
            'semua' => 'Semua Status',
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

    <!-- Card Filter Laporan Pembayaran -->
    <div class="card" style="margin-bottom: 22px;">
        <div class="card-head">
            <div>
                <h2>Filter Laporan Pembayaran</h2>
                <p>Data dihitung berdasarkan tanggal pembayaran yang diunggah konsumen.</p>
            </div>
            <a href="{{ route('laporan.pembayaran.cetak', request()->only(['dari', 'sampai', 'status'])) }}"
               target="_blank" rel="noopener" class="btn btn-primary btn-sm">
                🖨️ Cetak / Unduh PDF
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan.pembayaran') }}" method="GET" class="report-filter" style="grid-template-columns: repeat(3, 1fr) auto;">
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
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                    <a href="{{ route('laporan.pembayaran') }}" class="btn btn-outline">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Ringkasan Statistik Laporan -->
    <div class="report-stats">
        <div class="stat-card">
            <div class="label">Total Transaksi</div>
            <div class="value">{{ number_format($ringkasan['jumlah'], 0, ',', '.') }}</div>
            <div class="sub">Rp {{ number_format($ringkasan['total'], 0, ',', '.') }} seluruh nominal</div>
        </div>
        <div class="stat-card">
            <div class="label">Diterima (Valid)</div>
            <div class="value">Rp {{ number_format($ringkasan['valid'], 0, ',', '.') }}</div>
            <div class="sub">{{ number_format($ringkasan['valid_jumlah'], 0, ',', '.') }} transaksi disetujui</div>
        </div>
        <div class="stat-card">
            <div class="label">Menunggu Validasi</div>
            <div class="value">Rp {{ number_format($ringkasan['menunggu'], 0, ',', '.') }}</div>
            <div class="sub">Menanti pengecekan bendahara</div>
        </div>
        <div class="stat-card">
            <div class="label">Ditolak</div>
            <div class="value">Rp {{ number_format($ringkasan['ditolak'], 0, ',', '.') }}</div>
            <div class="sub">Perlu unggah ulang bukti</div>
        </div>
    </div>

    <!-- Tabel Rekap Data -->
    <div class="card">
        <div class="card-head">
            <div>
                <h2>Rekap Pembayaran</h2>
                <p>Periode {{ $periode['dari'] }} &ndash; {{ $periode['sampai'] }} &middot; {{ $statusLabels[$filter['status']] ?? 'Semua Status' }}</p>
            </div>
            <span class="badge">{{ $transaksi->total() }} DATA</span>
        </div>

        @if ($transaksi->isEmpty())
            <div class="empty">Tidak ada pembayaran pada periode dan filter yang dipilih.</div>
        @else
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th style="width:44px; text-align: center;">No</th>
                            <th style="width:110px;">Tanggal Bayar</th>
                            <th style="width:140px;">Kode Pesanan</th>
                            <th>Pemesan</th>
                            <th>Paket Wisata</th>
                            <th style="width:110px;">Metode</th>
                            <th style="width:130px; text-align: right;">Jumlah Bayar</th>
                            <th style="width:120px; text-align: center;">Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi as $index => $trx)
                            <tr>
                                <td style="text-align: center; color: #717171;">{{ $transaksi->firstItem() + $index }}</td>
                                <td>{{ $trx->tanggal_bayar?->translatedFormat('d M Y') ?? '-' }}</td>
                                <td>
                                    <strong style="color:#222;">{{ $trx->pemesanan->kode_pemesanan ?? '-' }}</strong>
                                </td>
                                <td>
                                    <div class="cell-title">{{ $trx->pemesanan->user->nama ?? '-' }}</div>
                                    <div class="cell-sub">📞 {{ $trx->pemesanan->no_hp ?? '-' }}</div>
                                </td>
                                <td>{{ $trx->pemesanan->paket->judul ?? '-' }}</td>
                                <td style="text-transform: capitalize;">{{ $trx->alat_pembayaran }}</td>
                                <td class="text-right money">Rp {{ number_format((float) $trx->jumlah_bayar, 0, ',', '.') }}</td>
                                <td style="text-align: center;">
                                    <span class="status status-{{ $trx->status }}">{{ ucfirst($trx->status) }}</span>
                                </td>
                                <td style="max-width: 220px; font-size: 12px; color: #555;">{{ $trx->catatan ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-right">Total Halaman Ini</th>
                            <th class="text-right money">Rp {{ number_format((float) $transaksi->sum('jumlah_bayar'), 0, ',', '.') }}</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="card-body" style="padding-top: 0;">
                {{ $transaksi->links('pagination.simple') }}
            </div>
        @endif
    </div>
@endsection
