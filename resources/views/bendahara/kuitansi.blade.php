<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuitansi {{ $transaksi->pemesanan->kode_pemesanan }} — PT. Puma Star Jaya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: #eef2f1;
            color: #0f172a;
            padding: 40px 20px;
            -webkit-font-smoothing: antialiased;
        }

        .toolbar {
            max-width: 780px; margin: 0 auto 16px; display: flex; justify-content: space-between;
            gap: 10px; align-items: center; flex-wrap: wrap;
        }
        .toolbar .info { font-size: 12px; color: #64748b; }
        .toolbar-actions { display: flex; gap: 8px; }
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 7px;
            border: 1px solid transparent; border-radius: 9px; padding: 9px 16px;
            font-family: inherit; font-size: 12.5px; font-weight: 700;
            text-decoration: none; cursor: pointer;
        }
        .btn-primary { background: #0f766e; color: #fff; }
        .btn-primary:hover { background: #0f4c46; }
        .btn-outline { background: #fff; border-color: #cbd5e1; color: #334155; }
        .btn-outline:hover { background: #f1f5f9; }

        .receipt {
            max-width: 780px; margin: 0 auto; background: #fff;
            border: 1px solid #e2e8f0; border-radius: 14px;
            padding: 44px 48px; box-shadow: 0 18px 50px -24px rgba(15, 23, 42, .35);
        }

        .head { display: flex; justify-content: space-between; gap: 24px; padding-bottom: 20px; border-bottom: 3px solid #0f766e; }
        .company { display: flex; gap: 12px; align-items: flex-start; }
        .logo {
            width: 46px; height: 46px; border-radius: 12px; flex-shrink: 0;
            background: linear-gradient(135deg, #0f766e, #0d9488);
            display: grid; place-items: center; color: #fff; font-weight: 800; font-size: 13px;
        }
        .company h1 { font-size: 17px; letter-spacing: -.01em; color: #0b3d3a; }
        .company .sub { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .08em; }
        .company address { font-style: normal; font-size: 11px; color: #64748b; margin-top: 6px; line-height: 1.5; }
        .doc-title { text-align: right; flex-shrink: 0; }
        .doc-title h2 { font-size: 20px; letter-spacing: .06em; text-transform: uppercase; color: #0f766e; }
        .doc-title .no { font-size: 11.5px; color: #64748b; margin-top: 4px; font-weight: 600; }

        .intro { margin-top: 26px; font-size: 13px; color: #334155; }
        .intro strong { color: #0f172a; }

        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 28px; margin-top: 22px; }
        .grid .full { grid-column: 1 / -1; }
        .item .label { display: block; font-size: 10px; text-transform: uppercase; letter-spacing: .07em; color: #64748b; font-weight: 800; margin-bottom: 2px; }
        .item .value { font-size: 13px; font-weight: 700; color: #0f172a; }
        .item .value.money { font-size: 18px; color: #0f4c46; }

        .amount-box {
            margin-top: 24px; border: 1px solid #99f6e4; background: #f0fdfa;
            border-radius: 10px; padding: 16px 18px;
        }
        .amount-box .row { display: flex; justify-content: space-between; gap: 14px; align-items: baseline; flex-wrap: wrap; }
        .amount-box .prefix { font-size: 11.5px; font-weight: 700; color: #0f766e; text-transform: uppercase; letter-spacing: .06em; }
        .amount-box .nominal { font-size: 24px; font-weight: 800; color: #0f4c46; letter-spacing: -.02em; }
        .amount-box .words { margin-top: 6px; font-size: 11.5px; color: #475569; font-style: italic; }

        .status-line { margin-top: 18px; display: flex; gap: 8px; align-items: center; }
        .stamp {
            display: inline-flex; align-items: center; gap: 6px;
            background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;
            font-size: 11px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
            padding: 5px 12px; border-radius: 999px;
        }

        .footer { margin-top: 34px; display: flex; justify-content: space-between; gap: 24px; align-items: flex-end; }
        .note { max-width: 360px; font-size: 10.5px; color: #94a3b8; line-height: 1.6; }
        .signature { text-align: center; min-width: 190px; }
        .signature .place { font-size: 11.5px; color: #475569; }
        .signature .role { font-size: 11.5px; color: #475569; margin-top: 2px; }
        .signature .space { height: 58px; }
        .signature .name { font-size: 12.5px; font-weight: 800; color: #0f172a; border-top: 1px solid #cbd5e1; padding-top: 5px; }

        .print-hint { max-width: 780px; margin: 14px auto 0; text-align: center; font-size: 11px; color: #94a3b8; }

        @media print {
            @page { size: A4; margin: 12mm; }
            body { background: #fff; padding: 0; }
            .toolbar, .print-hint { display: none !important; }
            .receipt { max-width: none; border: 0; border-radius: 0; box-shadow: none; padding: 0; }
        }

        @media (max-width: 640px) {
            .receipt { padding: 28px 22px; }
            .head { flex-direction: column; }
            .doc-title { text-align: left; }
            .grid { grid-template-columns: 1fr; }
            .grid .full { grid-column: auto; }
            .footer { flex-direction: column; align-items: flex-start; }
            .signature { text-align: left; }
        }
    </style>
</head>
<body>
    @php
        $pemesanan = $transaksi->pemesanan;
    @endphp

    <div class="toolbar">
        <span class="info">Kuitansi pembayaran yang sudah divalidasi bendahara.</span>
        <div class="toolbar-actions">
            <a href="{{ route('bendahara.pemesanan.show', $pemesanan) }}" class="btn btn-outline">&larr; Kembali</a>
            <button type="button" class="btn btn-primary" onclick="window.print()">&#128424; Cetak Kuitansi</button>
        </div>
    </div>

    <div class="receipt">
        <div class="head">
            <div class="company">
                <div class="logo">PSJ</div>
                <div>
                    <h1>PT. PUMA STAR JAYA</h1>
                    <div class="sub">Tour &amp; Travel</div>
                    <address>
                        Jl. Sultan Dumas No. A8&ndash;A9, Metro Selatan<br>
                        Kota Metro, Lampung<br>
                        Telp. 0812-3456-7890 &middot; info@pumastarjaya.test
                    </address>
                </div>
            </div>
            <div class="doc-title">
                <h2>Kuitansi</h2>
                <div class="no">No. KW/PSJ/{{ $transaksi->created_at->format('Ymd') }}/{{ str_pad((string) $transaksi->id, 4, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <p class="intro">
            Telah diterima pembayaran dari <strong>{{ $pemesanan->user->nama ?? '-' }}</strong>
            untuk pemesanan paket wisata dengan rincian sebagai berikut:
        </p>

        <div class="grid">
            <div class="item">
                <span class="label">Kode Pemesanan</span>
                <span class="value">{{ $pemesanan->kode_pemesanan }}</span>
            </div>
            <div class="item">
                <span class="label">Nama Rombongan</span>
                <span class="value">{{ $pemesanan->nama_rombongan ?: '-' }}</span>
            </div>
            <div class="item">
                <span class="label">Paket Wisata</span>
                <span class="value">{{ $pemesanan->paket->judul ?? '-' }}</span>
            </div>
            <div class="item">
                <span class="label">Destinasi</span>
                <span class="value">{{ $pemesanan->paket->destinasi ?? '-' }}</span>
            </div>
            <div class="item">
                <span class="label">Tanggal Berangkat</span>
                <span class="value">{{ $pemesanan->tanggal_berangkat->translatedFormat('d F Y') }}</span>
            </div>
            <div class="item">
                <span class="label">Jumlah Peserta</span>
                <span class="value">{{ $pemesanan->jumlah_pax }} orang</span>
            </div>
            <div class="item">
                <span class="label">Total Tagihan</span>
                <span class="value">Rp {{ number_format((float) $pemesanan->harga, 0, ',', '.') }}</span>
            </div>
            <div class="item">
                <span class="label">Metode Pembayaran</span>
                <span class="value">{{ ucfirst($transaksi->alat_pembayaran) }}</span>
            </div>
            <div class="item full">
                <span class="label">Tanggal Pembayaran</span>
                <span class="value">{{ $transaksi->tanggal_bayar?->translatedFormat('d F Y') ?? '-' }}</span>
            </div>
        </div>

        <div class="amount-box">
            <div class="row">
                <span class="prefix">Jumlah Dibayar</span>
                <span class="nominal">Rp {{ number_format((float) $transaksi->jumlah_bayar, 0, ',', '.') }}</span>
            </div>
            <div class="words">Terbilang: {{ ucfirst($terbilang) }}</div>
        </div>

        <div class="status-line">
            <span class="stamp">&#10003; Pembayaran Valid</span>
        </div>

        <div class="footer">
            <p class="note">
                Kuitansi ini diterbitkan secara otomatis oleh Sistem Informasi Pemesanan Paket Wisata
                PT. Puma Star Jaya dan sah tanpa tanda tangan basah.
            </p>
            <div class="signature">
                <div class="place">Metro, {{ now()->translatedFormat('d F Y') }}</div>
                <div class="role">Bendahara,</div>
                <div class="space"></div>
                <div class="name">{{ auth()->user()->nama }}</div>
            </div>
        </div>
    </div>

    <p class="print-hint">Gunakan tombol Cetak Kuitansi atau tekan Ctrl+P untuk mencetak / menyimpan sebagai PDF.</p>
</body>
</html>
