@extends('layouts.app')

@section('title', 'Detail Pesanan '.$pemesanan->kode_pemesanan.' — PT. Puma Star Jaya')

@push('styles')
<style>
    .detail-page { padding: 34px 0 64px; }
    .breadcrumb { font-size: 12.5px; color: var(--slate-500); margin-bottom: 18px; }
    .breadcrumb a { color: var(--teal-700); font-weight: 600; text-decoration: none; }
    .breadcrumb a:hover { text-decoration: underline; }

    .detail-grid { display: grid; grid-template-columns: 1fr 350px; gap: 26px; align-items: start; }

    .panel { background: #fff; border: 1px solid var(--slate-200); border-radius: 16px; box-shadow: var(--shadow-sm); overflow: hidden; }
    .panel-head { padding: 20px 24px; border-bottom: 1px solid var(--slate-200); display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; }
    .panel-head h1 { font-size: 19px; letter-spacing: -.02em; }
    .panel-head p { margin-top: 4px; font-size: 12.5px; color: var(--slate-500); }
    .panel-body { padding: 24px; }

    .data-list { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 24px; }
    .data-list .full { grid-column: 1 / -1; }
    .data-item span { display: block; font-size: 11.5px; font-weight: 600; color: var(--slate-500); margin-bottom: 3px; }
    .data-item strong { font-size: 13.5px; font-weight: 700; color: var(--slate-900); }
    .data-item p { font-size: 13.5px; color: var(--slate-700); }

    .summary-card { position: sticky; top: 96px; }
    .summary-top { background: linear-gradient(135deg, var(--teal-800), var(--teal-900)); color: #fff; padding: 22px; }
    .summary-top .label { font-size: 11px; text-transform: uppercase; letter-spacing: .1em; color: #99f6e4; font-weight: 800; }
    .summary-top .total { font-size: 27px; font-weight: 800; letter-spacing: -.03em; margin-top: 4px; }
    .summary-top .status { margin-top: 10px; }
    .summary-body { padding: 20px; }
    .summary-list { list-style: none; display: grid; gap: 11px; }
    .summary-list li { display: flex; justify-content: space-between; gap: 12px; font-size: 12.5px; }
    .summary-list span:first-child { color: var(--slate-500); }
    .summary-list span:last-child { font-weight: 700; text-align: right; }
    .summary-body .btn { margin-top: 16px; }
    .note { margin-top: 13px; font-size: 11.5px; color: var(--slate-500); line-height: 1.6; }

    .history-title { font-size: 15px; letter-spacing: -.01em; margin-bottom: 4px; }
    .history-sub { font-size: 12.5px; color: var(--slate-500); margin-bottom: 16px; }
    .history-list { display: grid; gap: 12px; }
    .history-item { border: 1px solid var(--slate-200); border-radius: 12px; padding: 14px 16px; }
    .history-item.rejected { border-color: #fecaca; background: #fff7f7; }
    .history-top { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
    .history-amount { font-size: 15px; font-weight: 800; color: var(--teal-800); }
    .history-meta { display: flex; flex-wrap: wrap; gap: 4px 16px; margin-top: 6px; font-size: 12px; color: var(--slate-500); }
    .history-note { margin-top: 8px; padding: 9px 11px; background: var(--slate-50); border-radius: 8px; font-size: 12px; color: var(--slate-700); }
    .history-item.rejected .history-note { background: #fee2e2; color: #991b1b; }
    .proof-link { display: inline-flex; align-items: center; gap: 6px; margin-top: 8px; font-size: 12px; font-weight: 700; color: var(--teal-700); text-decoration: none; }
    .proof-link:hover { text-decoration: underline; }
    .empty-note { border: 1px dashed var(--slate-300); border-radius: 12px; padding: 22px; text-align: center; font-size: 12.5px; color: var(--slate-500); }

    @media (max-width: 980px) {
        .detail-grid { grid-template-columns: 1fr; }
        .summary-card { position: static; }
    }
    @media (max-width: 600px) {
        .data-list { grid-template-columns: 1fr; }
        .data-list .full { grid-column: auto; }
    }
</style>
@endpush

@section('content')
    @php $status = $pemesanan->statusPembayaran(); @endphp

    <div class="container detail-page">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> &nbsp;/&nbsp;
            <a href="{{ route('konsumen.pemesanan.index') }}">Pesanan Saya</a> &nbsp;/&nbsp;
            <span>{{ $pemesanan->kode_pemesanan }}</span>
        </div>

        <div class="detail-grid">
            <div>
                <div class="panel" style="margin-bottom: 22px;">
                    <div class="panel-head">
                        <div>
                            <h1>Detail Pemesanan</h1>
                            <p>Kode pesanan <strong style="color:var(--teal-700);">{{ $pemesanan->kode_pemesanan }}</strong></p>
                        </div>
                        <span class="status status-{{ $status }}">{{ $pemesanan->labelStatus() }}</span>
                    </div>

                    <div class="panel-body">
                        <div class="data-list">
                            <div class="data-item">
                                <span>Paket Wisata</span>
                                <strong>{{ $pemesanan->paket->judul ?? 'Paket tidak tersedia' }}</strong>
                            </div>
                            <div class="data-item">
                                <span>Destinasi</span>
                                <strong>{{ $pemesanan->paket->destinasi ?? '-' }}</strong>
                            </div>
                            <div class="data-item">
                                <span>Tanggal Berangkat</span>
                                <strong>{{ $pemesanan->tanggal_berangkat->translatedFormat('d F Y') }}</strong>
                            </div>
                            <div class="data-item">
                                <span>Jumlah Peserta</span>
                                <strong>{{ $pemesanan->jumlah_pax }} orang</strong>
                            </div>
                            <div class="data-item">
                                <span>Nama Rombongan</span>
                                <strong>{{ $pemesanan->nama_rombongan ?: '-' }}</strong>
                            </div>
                            <div class="data-item">
                                <span>Nomor HP</span>
                                <strong>{{ $pemesanan->no_hp }}</strong>
                            </div>
                            @if ($pemesanan->kupon)
                                <div class="data-item">
                                    <span>Kupon Digunakan</span>
                                    <strong>{{ $pemesanan->kupon->kode }} (&minus; Rp {{ number_format((float) $pemesanan->kupon->potongan, 0, ',', '.') }})</strong>
                                </div>
                            @endif
                            <div class="data-item">
                                <span>Tanggal Pemesanan</span>
                                <strong>{{ $pemesanan->created_at->translatedFormat('d F Y, H:i') }} WIB</strong>
                            </div>
                            @if ($pemesanan->catatan)
                                <div class="data-item full">
                                    <span>Catatan</span>
                                    <p>{{ $pemesanan->catatan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-body">
                        <h2 class="history-title">Riwayat Pembayaran</h2>
                        <p class="history-sub">Setiap bukti yang Anda unggah akan tercatat di sini beserta hasil validasi bendahara.</p>

                        @if ($pemesanan->transaksi->isEmpty())
                            <div class="empty-note">Belum ada bukti pembayaran yang diunggah.</div>
                        @else
                            <div class="history-list">
                                @foreach ($pemesanan->transaksi->sortByDesc('created_at') as $trx)
                                    <div class="history-item {{ $trx->status === 'ditolak' ? 'rejected' : '' }}">
                                        <div class="history-top">
                                            <span class="status status-{{ $trx->status }}">{{ ucfirst($trx->status) }}</span>
                                            <span class="history-amount">Rp {{ number_format((float) $trx->jumlah_bayar, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="history-meta">
                                            <span>Metode: <strong style="color:var(--slate-700);">{{ ucfirst($trx->alat_pembayaran) }}</strong></span>
                                            <span>Tanggal bayar: <strong style="color:var(--slate-700);">{{ $trx->tanggal_bayar?->translatedFormat('d M Y') ?? '-' }}</strong></span>
                                            <span>Diunggah: <strong style="color:var(--slate-700);">{{ $trx->created_at->translatedFormat('d M Y H:i') }}</strong></span>
                                        </div>
                                        @if ($trx->catatan)
                                            <div class="history-note">
                                                <strong>Catatan bendahara:</strong> {{ $trx->catatan }}
                                            </div>
                                        @endif
                                        @if ($trx->bukti_pembayaran)
                                            <a href="{{ asset('storage/'.$trx->bukti_pembayaran) }}" target="_blank" rel="noopener" class="proof-link">
                                                &#128196; Lihat bukti pembayaran
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <aside class="panel summary-card">
                <div class="summary-top">
                    <div class="label">Total Tagihan</div>
                    <div class="total">Rp {{ number_format((float) $pemesanan->harga, 0, ',', '.') }}</div>
                    <span class="status status-{{ $status }}">{{ $pemesanan->labelStatus() }}</span>
                </div>
                <div class="summary-body">
                    <ul class="summary-list">
                        <li><span>Harga paket</span><span>Rp {{ number_format((float) ($pemesanan->paket->harga ?? 0), 0, ',', '.') }} / orang</span></li>
                        <li><span>Jumlah peserta</span><span>{{ $pemesanan->jumlah_pax }} orang</span></li>
                        <li><span>Status</span><span>{{ $pemesanan->labelStatus() }}</span></li>
                    </ul>

                    @if ($pemesanan->bisaBayar())
                        @if ($snapToken)
                            <button type="button" id="pay-button" class="btn btn-amber btn-block" style="font-size:14px; margin-bottom: 8px;">
                                ⚡ Bayar Sekarang via Midtrans
                            </button>
                        @endif
                        <a href="{{ route('konsumen.pemesanan.bayar', $pemesanan) }}" class="btn btn-primary btn-block">
                            Halaman Pembayaran
                        </a>
                        <p class="note">
                            Pembayaran otomatis via <strong>Midtrans</strong> (GoPay, QRIS, Virtual Account BCA/Mandiri/BRI/BNI). Tidak perlu kirim/unggah bukti transfer.
                        </p>
                    @elseif ($status === 'menunggu')
                        <p class="note" style="margin-top:16px;">
                            Bukti pembayaran Anda sudah diterima dan sedang menunggu validasi bendahara. Pantau halaman ini secara berkala.
                        </p>
                    @else
                        <p class="note" style="margin-top:16px;">
                            Pembayaran Anda telah divalidasi. Terima kasih, pesanan Anda sudah dikonfirmasi.
                        </p>
                    @endif

                    <a href="{{ route('konsumen.pemesanan.index') }}" class="btn btn-outline btn-block" style="margin-top:10px;">Kembali ke Pesanan</a>
                </div>
            </aside>
        </div>
    </div>

    @if ($snapToken)
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        <script>
            function kirimStatusKeServer(result) {
                fetch("{{ route('konsumen.pemesanan.syncMidtrans', $pemesanan) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ result: result })
                }).finally(() => {
                    window.location.reload();
                });
            }

            document.getElementById('pay-button').onclick = function () {
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function (result) {
                        kirimStatusKeServer(result);
                    },
                    onPending: function (result) {
                        kirimStatusKeServer(result);
                    },
                    onError: function (result) {
                        alert('Pembayaran gagal atau terjadi kesalahan.');
                        window.location.reload();
                    },
                    onClose: function () {
                        // Konsumen menutup pop-up tanpa menyelesaikan pembayaran
                    }
                });
            };
        </script>
    @endif
@endsection
