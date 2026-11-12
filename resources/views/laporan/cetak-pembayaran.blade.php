<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembayaran — PT. Puma Star Jaya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            background: #eef2f1; color: #0f172a;
            font-size: 12px; padding: 26px 18px;
            -webkit-font-smoothing: antialiased;
        }

        .toolbar {
            max-width: 1120px; margin: 0 auto 14px;
            display: flex; justify-content: space-between; align-items: center;
            gap: 10px; flex-wrap: wrap;
        }
        .toolbar .info { font-size: 11.5px; color: #64748b; }
        .toolbar-actions { display: flex; gap: 8px; }
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 7px;
            border: 1px solid transparent; border-radius: 9px; padding: 9px 16px;
            font-family: inherit; font-size: 12px; font-weight: 700;
            text-decoration: none; cursor: pointer;
        }
        .btn-primary { background: #0f766e; color: #fff; }
        .btn-primary:hover { background: #0f4c46; }
        .btn-outline { background: #fff; border-color: #cbd5e1; color: #334155; }
        .btn-outline:hover { background: #f1f5f9; }

        .sheet {
            max-width: 1120px; margin: 0 auto; background: #fff;
            border: 1px solid #e2e8f0; border-radius: 12px;
            padding: 30px 34px 26px; box-shadow: 0 18px 50px -24px rgba(15, 23, 42, .3);
        }

        .head { display: flex; justify-content: space-between; gap: 24px; padding-bottom: 14px; border-bottom: 3px solid #0f766e; }
        .company { display: flex; gap: 12px; align-items: flex-start; }
        .logo {
            width: 44px; height: 44px; border-radius: 11px; flex-shrink: 0;
            background: linear-gradient(135deg, #0f766e, #0d9488);
            display: grid; place-items: center; color: #fff; font-weight: 800; font-size: 12px;
        }
        .company h1 { font-size: 15px; letter-spacing: -.01em; color: #0b3d3a; }
        .company .sub { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .09em; }
        .company address { font-style: normal; font-size: 10px; color: #64748b; margin-top: 5px; line-height: 1.5; }
        .doc-title { text-align: right; flex-shrink: 0; }
        .doc-title h2 { font-size: 17px; letter-spacing: .04em; text-transform: uppercase; color: #0f766e; }
        .doc-title .no { font-size: 10.5px; color: #64748b; margin-top: 3px; font-weight: 600; }

        .meta {
            display: flex; flex-wrap: wrap; gap: 5px 28px;
            margin-top: 14px; font-size: 11px; color: #475569;
        }
        .meta strong { color: #0f172a; }

        .summary {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;
            margin: 16px 0 14px;
        }
        .summary .box {
            border: 1px solid #e2e8f0; border-radius: 8px; padding: 9px 12px; background: #f8fafc;
        }
        .summary .label { font-size: 8.5px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; color: #64748b; }
        .summary .value { font-size: 14px; font-weight: 800; color: #0f4c46; margin-top: 2px; }
        .summary .sub { font-size: 8.5px; color: #64748b; margin-top: 1px; }

        table { width: 100%; border-collapse: collapse; font-size: 10.5px; }
        thead th {
            background: #0f4c46; color: #fff; text-align: left;
            font-size: 8.5px; text-transform: uppercase; letter-spacing: .05em;
            padding: 7px 8px; border: 1px solid #0f4c46;
        }
        tbody td { padding: 6px 8px; border: 1px solid #e2e8f0; vertical-align: top; }
        tbody tr:nth-child(even) td { background: #f8fafc; }
        tfoot td, tfoot th {
            padding: 7px 8px; border: 1px solid #cbd5e1; background: #f0fdfa;
            font-size: 10.5px; font-weight: 800; color: #0f4c46;
        }
        .money { white-space: nowrap; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .status { font-weight: 700; white-space: nowrap; }
        .status-valid { color: #166534; }
        .status-menunggu { color: #92400e; }
        .status-ditolak { color: #991b1b; }
        .muted { color: #64748b; }
        .empty-row td { text-align: center; padding: 22px; color: #64748b; font-style: italic; }

        .footer { margin-top: 24px; display: flex; justify-content: space-between; gap: 24px; align-items: flex-start; }
        .note { max-width: 460px; font-size: 9px; color: #94a3b8; line-height: 1.6; }
        .signatures { display: flex; gap: 54px; }
        .signature { text-align: center; min-width: 170px; }
        .signature .place { font-size: 10px; color: #475569; }
        .signature .role { font-size: 10px; color: #475569; margin-top: 1px; }
        .signature .space { height: 48px; }
        .signature .name { font-size: 11px; font-weight: 800; color: #0f172a; border-top: 1px solid #94a3b8; padding-top: 4px; }

        @media print {
            @page { size: A4 landscape; margin: 10mm; }
            body { background: #fff; padding: 0; font-size: 9.5px; }
            .sheet { max-width: none; border: 0; border-radius: 0; box-shadow: none; padding: 0; }
            .no-print { display: none !important; }
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; }
            .summary { break-inside: avoid; }
        }

        @media (max-width: 720px) {
            .sheet { padding: 22px 18px; }
            .head { flex-direction: column; }
            .doc-title { text-align: left; }
            .summary { grid-template-columns: repeat(2, 1fr); }
            .footer { flex-direction: column; }
        }
    </style>
</head>
<body>
    @php
        $statusLabels = [
            'semua' => 'Semua Status',
            'menunggu' => 'Menunggu Validasi',
            'valid' => 'Pembayaran Valid',
            'ditolak' => 'Pembayaran Ditolak',
        ];
    @endphp

    <div class="toolbar no-print">
        <span class="info">Pratinjau laporan. Gunakan tombol cetak untuk mencetak atau menyimpan sebagai PDF.</span>
        <div class="toolbar-actions">
            <a href="{{ route('laporan.pembayaran', request()->only(['dari', 'sampai', 'status'])) }}" class="btn btn-outline">&larr; Kembali</a>
            <button type="button" class="btn btn-primary" onclick="window.print()">&#128424; Cetak Laporan</button>
        </div>
    </div>

    <div class="sheet">
        <div class="head">
            <div class="company">
                <div class="logo">PSJ</div>
                <div>
                    <h1>PT. PUMA STAR JAYA</h1>
                    <div class="sub">Tour &amp; Travel</div>
                    <address>
                        Jl. Sultan Dumas No. A8&ndash;A9, Metro Selatan, Kota Metro, Lampung<br>
                        Telp. 0812-3456-7890 &middot; info@pumastarjaya.test
                    </address>
                </div>
            </div>
            <div class="doc-title">
                <h2>Laporan Pembayaran</h2>
                <div class="no">Keuangan Paket Wisata</div>
            </div>
        </div>

        <div class="meta">
            <span>Periode: <strong>{{ $periode['dari'] }} &ndash; {{ $periode['sampai'] }}</strong></span>
            <span>Status: <strong>{{ $statusLabels[$filter['status']] ?? 'Semua Status' }}</strong></span>
            <span>Dicetak: <strong>{{ $dicetak->translatedFormat('d F Y, H:i') }} WIB</strong></span>
        </div>

        <div class="summary">
            <div class="box">
                <div class="label">Total Transaksi</div>
                <div class="value">{{ number_format($ringkasan['jumlah'], 0, ',', '.') }}</div>
                <div class="sub">Rp {{ number_format($ringkasan['total'], 0, ',', '.') }}</div>
            </div>
            <div class="box">
                <div class="label">Diterima (Valid)</div>
                <div class="value">Rp {{ number_format($ringkasan['valid'], 0, ',', '.') }}</div>
                <div class="sub">{{ number_format($ringkasan['valid_jumlah'], 0, ',', '.') }} transaksi</div>
            </div>
            <div class="box">
                <div class="label">Menunggu Validasi</div>
                <div class="value">Rp {{ number_format($ringkasan['menunggu'], 0, ',', '.') }}</div>
            </div>
            <div class="box">
                <div class="label">Ditolak</div>
                <div class="value">Rp {{ number_format($ringkasan['ditolak'], 0, ',', '.') }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 26px;">No</th>
                    <th>Tanggal Bayar</th>
                    <th>Kode Pemesanan</th>
                    <th>Pemesan</th>
                    <th>Paket Wisata</th>
                    <th>Metode</th>
                    <th class="text-right">Jumlah Bayar</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksi as $index => $trx)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $trx->tanggal_bayar?->translatedFormat('d/m/Y') ?? '-' }}</td>
                        <td>{{ $trx->pemesanan->kode_pemesanan ?? '-' }}</td>
                        <td>{{ $trx->pemesanan->user->nama ?? '-' }}</td>
                        <td>{{ $trx->pemesanan->paket->judul ?? '-' }}</td>
                        <td style="text-transform: capitalize;">{{ $trx->alat_pembayaran }}</td>
                        <td class="text-right money">Rp {{ number_format((float) $trx->jumlah_bayar, 0, ',', '.') }}</td>
                        <td><span class="status status-{{ $trx->status }}">{{ ucfirst($trx->status) }}</span></td>
                        <td>{{ $trx->catatan ?: '-' }}</td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="9">Tidak ada data pembayaran pada periode dan filter yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6" class="text-right">TOTAL</th>
                    <th class="text-right money">Rp {{ number_format((float) $transaksi->sum('jumlah_bayar'), 0, ',', '.') }}</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <p class="note">
                Laporan ini dibuat otomatis oleh Sistem Informasi Pemesanan Paket Wisata PT. Puma Star Jaya.
                Data dihitung berdasarkan tanggal pembayaran yang diunggah konsumen beserta status validasinya.
            </p>
            <div class="signatures">
                <div class="signature">
                    <div class="place">Metro, {{ $dicetak->translatedFormat('d F Y') }}</div>
                    <div class="role">Mengetahui, Direktur</div>
                    <div class="space"></div>
                    <div class="name">{{ $penandatangan['direktur'] }}</div>
                </div>
                <div class="signature">
                    <div class="place">&nbsp;</div>
                    <div class="role">Bendahara</div>
                    <div class="space"></div>
                    <div class="name">{{ $penandatangan['bendahara'] }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
