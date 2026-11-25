@extends('layouts.app')

@section('title', 'Pembayaran Pesanan — PT. Puma Star Jaya')

@push('styles')
<style>
    .pay-page { padding: 36px 0 70px; }
    .breadcrumb { font-size: 13px; color: #717171; margin-bottom: 20px; }
    .breadcrumb a { color: #222222; font-weight: 600; text-decoration: underline; }

    .pay-grid { display: grid; grid-template-columns: 1fr 390px; gap: 32px; align-items: start; }

    .panel { background: #fff; border: 1px solid #ebebeb; border-radius: 20px; box-shadow: var(--shadow-subtle); overflow: hidden; }
    .panel-head { padding: 24px 28px; border-bottom: 1px solid #f0f0f0; }
    .panel-head h1 { font-family: 'Outfit', sans-serif; font-size: 22px; font-weight: 800; color: #222222; letter-spacing: -.02em; }
    .panel-head p { margin-top: 5px; font-size: 13.5px; color: #717171; }
    .panel-body { padding: 28px; }

    .summary-card { position: sticky; top: 96px; overflow: hidden; border-radius: 20px; border: 1px solid #ebebeb; }
    .summary-top { background: #222222; color: #fff; padding: 26px; }
    .summary-top .label { font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: #b0b0b0; font-weight: 800; }
    .summary-top .total { font-family: 'Outfit', sans-serif; font-size: 32px; font-weight: 900; letter-spacing: -.03em; margin-top: 4px; color: #ffffff; }
    .summary-top .code { margin-top: 8px; font-size: 13px; color: #dddddd; }
    .summary-body { padding: 24px; background: #ffffff; }
    .summary-list { list-style: none; display: grid; gap: 12px; }
    .summary-list li { display: flex; justify-content: space-between; gap: 12px; font-size: 13px; }
    .summary-list span:first-child { color: #717171; }
    .summary-list span:last-child { font-weight: 700; color: #222222; text-align: right; }

    .midtrans-box {
        background: #222222;
        color: #fff;
        border-radius: 18px;
        padding: 34px 28px;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.15);
        text-align: center;
    }

    .payment-channels {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        margin: 22px 0 28px;
    }
    .channel-badge {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-pay-midtrans {
        background: #ff385c;
        color: #ffffff;
        font-family: inherit;
        font-size: 15px;
        font-weight: 800;
        padding: 14px 28px;
        border-radius: var(--radius-full);
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(255, 56, 92, 0.35);
        transition: all .2s;
    }
    .btn-pay-midtrans:hover {
        background: #e00b41;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(255, 56, 92, 0.45);
    }
        background: #f59e0b;
        color: #78350f;
        font-weight: 800;
        font-size: 16px;
        padding: 16px 36px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 6px 20px rgba(245, 158, 11, .45);
        transition: all 0.2s;
    }
    .btn-pay-midtrans:hover {
        background: #d97706;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(245, 158, 11, .55);
    }

    @media (max-width: 980px) {
        .pay-grid { grid-template-columns: 1fr; }
        .summary-card { position: static; }
    }
</style>
@endpush

@section('content')
    <div class="container pay-page">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> &nbsp;/&nbsp;
            <a href="{{ route('konsumen.pemesanan.index') }}">Pesanan Saya</a> &nbsp;/&nbsp;
            <a href="{{ route('konsumen.pemesanan.show', $pemesanan) }}">{{ $pemesanan->kode_pemesanan }}</a> &nbsp;/&nbsp;
            <span>Pembayaran</span>
        </div>

        <div class="pay-grid">
            <div class="panel">
                <div class="panel-head">
                    <h1>Pembayaran Pesanan</h1>
                    <p>Selesaikan pembayaran secara instan dan otomatis melalui Payment Gateway resmi.</p>
                </div>

                <div class="panel-body">
                    <div class="midtrans-box">
                        <span style="display: inline-block; background: rgba(253, 230, 138, .25); color: #fde68a; font-size: 12px; font-weight: 800; padding: 5px 14px; border-radius: 999px; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 12px;">
                            ⚡ Pembayaran Otomatis &amp; Real-time
                        </span>
                        
                        <h2 style="font-size: 24px; font-weight: 800; color: #fff; margin-bottom: 8px;">
                            Bayar Online via Midtrans
                        </h2>
                        
                        <p style="font-size: 14px; color: #ccfbf1; margin: 0 auto; max-width: 520px; line-height: 1.6;">
                            Tidak perlu lagi mengirim atau mengunggah bukti pembayaran manual. Status pesanan Anda akan otomatis lunas terkonfirmasi detik itu juga begitu Anda selesai membayar.
                        </p>

                        <div class="payment-channels">
                            <span class="channel-badge">📱 QRIS / GoPay / ShopeePay</span>
                            <span class="channel-badge">🏦 Virtual Account BCA</span>
                            <span class="channel-badge">🏦 Virtual Account Mandiri</span>
                            <span class="channel-badge">🏦 Virtual Account BRI</span>
                            <span class="channel-badge">🏦 Virtual Account BNI</span>
                        </div>

                        @if ($snapToken)
                            <button type="button" id="pay-button" class="btn-pay-midtrans">
                                <span>Bayar Sekarang (Rp {{ number_format((float) $pemesanan->harga, 0, ',', '.') }})</span> &rarr;
                            </button>
                        @else
                            <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); border-radius: 10px; padding: 12px; color: #fecaca; font-size: 13px;">
                                Gagal memuat token pembayaran Midtrans. Mohon refresh halaman atau pastikan server terhubung ke internet.
                            </div>
                        @endif
                    </div>

                    <div style="margin-top: 24px; display: flex; justify-content: flex-start;">
                        <a href="{{ route('konsumen.pemesanan.show', $pemesanan) }}" class="btn btn-outline">
                            &larr; Kembali ke Detail Pesanan
                        </a>
                    </div>
                </div>
            </div>

            <aside class="panel summary-card">
                <div class="summary-top">
                    <div class="label">Total Tagihan</div>
                    <div class="total">Rp {{ number_format((float) $pemesanan->harga, 0, ',', '.') }}</div>
                    <div class="code">{{ $pemesanan->kode_pemesanan }}</div>
                </div>
                <div class="summary-body">
                    <ul class="summary-list">
                        <li><span>Paket</span><span>{{ $pemesanan->paket->judul ?? '-' }}</span></li>
                        <li><span>Berangkat</span><span>{{ $pemesanan->tanggal_berangkat->translatedFormat('d M Y') }}</span></li>
                        <li><span>Peserta</span><span>{{ $pemesanan->jumlah_pax }} orang</span></li>
                        <li><span>Status</span><span>{{ $pemesanan->labelStatus() }}</span></li>
                    </ul>

                    <div style="margin-top: 20px; padding: 14px; background: var(--slate-50); border-radius: 12px; border: 1px solid var(--slate-200); font-size: 12px; color: var(--slate-600); line-height: 1.6;">
                        <strong>🔒 Transaksi Terenkripsi &amp; Aman</strong><br>
                        Sistem terintegrasi resmi dengan Midtrans Snap Payment. Pembayaran langsung diverifikasi tanpa perantara.
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
@if ($snapToken)
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    async function kirimStatusKeServer(result) {
        const btn = document.getElementById('pay-button');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span>Memverifikasi Pembayaran...</span>';
        }

        try {
            await fetch("{{ route('konsumen.pemesanan.syncMidtrans', $pemesanan) }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ result: result })
            });
        } catch (err) {
            console.error("Gagal sinkronisasi:", err);
        } finally {
            window.location.href = "{{ route('konsumen.pemesanan.show', $pemesanan) }}";
        }
    }

    const payBtn = document.getElementById('pay-button');
    if (payBtn) {
        payBtn.onclick = function () {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) {
                    kirimStatusKeServer(result);
                },
                onPending: function (result) {
                    kirimStatusKeServer(result);
                },
                onError: function (result) {
                    kirimStatusKeServer(result);
                },
                onClose: function () {
                    // Konsumen menutup pop-up, cek apakah di simulator sudah dibayar
                    kirimStatusKeServer({ transaction_status: 'check' });
                }
            });
        };
    }
</script>
@endif
@endpush
