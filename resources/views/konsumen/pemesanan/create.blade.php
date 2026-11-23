@extends('layouts.app')

@section('title', 'Pesan '.$paket->judul.' — PT. Puma Star Jaya')

@push('styles')
<style>
    .booking-page { padding: 36px 0 70px; }
    .breadcrumb { font-size: 13px; color: #717171; margin-bottom: 20px; }
    .breadcrumb a { color: #222222; font-weight: 600; text-decoration: underline; }

    .booking-grid { display: grid; grid-template-columns: 1fr 390px; gap: 32px; align-items: start; }

    .panel { background: #fff; border: 1px solid #ebebeb; border-radius: 20px; box-shadow: var(--shadow-subtle); }
    .panel-head { padding: 24px 28px; border-bottom: 1px solid #f0f0f0; }
    .panel-head h1 { font-family: 'Outfit', sans-serif; font-size: 22px; font-weight: 800; color: #222222; letter-spacing: -.02em; }
    .panel-head p { margin-top: 5px; font-size: 13.5px; color: #717171; }
    .panel-body { padding: 28px; }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 20px; }
    .field { margin-bottom: 18px; }
    .field.full { grid-column: 1 / -1; }
    .field label { display: block; font-size: 13px; font-weight: 700; color: #222222; margin-bottom: 6px; }
    .field label .opt { font-weight: 500; color: #888888; font-size: 11.5px; }
    .field input, .field textarea, .field select {
        width: 100%; border: 1.5px solid #dddddd; border-radius: 12px;
        padding: 11px 14px; font-family: inherit; font-size: 13.5px; color: #222222;
        background: #fff; transition: border-color .18s, box-shadow .18s;
    }
    .field textarea { resize: vertical; min-height: 96px; }
    .field input:focus, .field textarea:focus, .field select:focus {
        outline: none; border-color: #222222; box-shadow: 0 0 0 3px rgba(0, 0, 0, .08);
    }
    .field .error { margin-top: 5px; font-size: 12px; color: var(--danger); font-weight: 600; }
    .field .hint { margin-top: 5px; font-size: 12px; color: #717171; }

    .summary { position: sticky; top: 96px; overflow: hidden; border-radius: 20px; border: 1px solid #ebebeb; }
    .summary-media {
        height: 140px; display: flex; align-items: flex-end; padding: 18px;
        color: #fff; position: relative;
        background-size: cover; background-position: center;
    }
    .summary-media::after { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.7) 100%); }
    .summary-media span { position: relative; z-index: 1; font-size: 11px; font-weight: 800; background: #ffffff; color: #222222; padding: 4px 10px; border-radius: var(--radius-full); text-transform: uppercase; }
    .summary-body { padding: 24px; background: #ffffff; }
    .summary-body h2 { font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 800; color: #222222; line-height: 1.35; }
    .summary-body .dest { font-size: 13px; color: #717171; margin-top: 4px; }
    .summary-list { list-style: none; display: grid; gap: 11px; margin: 18px 0; padding: 18px 0; border-top: 1px dashed #ebebeb; border-bottom: 1px dashed #ebebeb; }
    .summary-list li { display: flex; justify-content: space-between; gap: 12px; font-size: 13px; }
    .summary-list span:first-child { color: #717171; }
    .summary-list span:last-child { font-weight: 700; color: #222222; text-align: right; }
    .total-row { display: flex; justify-content: space-between; align-items: baseline; gap: 12px; }
    .total-row .label { font-size: 12.5px; font-weight: 600; color: #717171; }
    .total-row .value { font-family: 'Outfit', sans-serif; font-size: 26px; font-weight: 900; color: #222222; letter-spacing: -.03em; }
    .note-box { margin-top: 18px; background: #f7f7f7; border: 1px solid #ebebeb; border-radius: 12px; padding: 12px 14px; font-size: 12px; color: #555555; line-height: 1.5; }

    .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 12px; padding-top: 18px; border-top: 1px solid #f0f0f0; }

    @media (max-width: 980px) {
        .booking-grid { grid-template-columns: 1fr; }
        .summary { position: static; }
    }
    @media (max-width: 600px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; }
    }
</style>
@endpush

@section('content')
    <div class="container booking-page">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> &nbsp;/&nbsp;
            <a href="{{ route('paket.index') }}">Paket Wisata</a> &nbsp;/&nbsp;
            <a href="{{ route('paket.show', $paket) }}">{{ $paket->judul }}</a> &nbsp;/&nbsp;
            <span>Pemesanan</span>
        </div>

        <div class="booking-grid">
            <div class="panel">
                <div class="panel-head">
                    <h1>Formulir Pemesanan</h1>
                    <p>Lengkapi data perjalanan Anda. Setelah disimpan, Anda akan diarahkan untuk mengunggah bukti pembayaran.</p>
                </div>

                <div class="panel-body">
                    <form action="{{ route('konsumen.pemesanan.store', $paket) }}" method="POST">
                        @csrf

                        <div class="form-grid">
                            <div class="field">
                                <label for="tanggal_berangkat">Tanggal Berangkat</label>
                                <input type="date" id="tanggal_berangkat" name="tanggal_berangkat"
                                       value="{{ old('tanggal_berangkat') }}" min="{{ now()->toDateString() }}">
                                @error('tanggal_berangkat') <div class="error">{{ $message }}</div> @enderror
                            </div>

                            <div class="field">
                                <label for="jumlah_pax">Jumlah Peserta (orang)</label>
                                <input type="number" id="jumlah_pax" name="jumlah_pax" min="1" max="200"
                                       value="{{ old('jumlah_pax', 1) }}">
                                @error('jumlah_pax') <div class="error">{{ $message }}</div> @enderror
                            </div>

                            <div class="field">
                                <label for="nama_rombongan">Nama Rombongan <span class="opt">(opsional)</span></label>
                                <input type="text" id="nama_rombongan" name="nama_rombongan"
                                       value="{{ old('nama_rombongan') }}" placeholder="Contoh: Keluarga Budi / SMAN 1 Metro">
                                @error('nama_rombongan') <div class="error">{{ $message }}</div> @enderror
                            </div>

                            <div class="field">
                                <label for="no_hp">Nomor HP Aktif</label>
                                <input type="text" id="no_hp" name="no_hp"
                                       value="{{ old('no_hp', auth()->user()->no_hp) }}" placeholder="08xxxxxxxxxx">
                                @error('no_hp') <div class="error">{{ $message }}</div> @enderror
                            </div>

                            <div class="field full">
                                <label for="kode_kupon">Kode Kupon <span class="opt">(opsional)</span></label>
                                <input type="text" id="kode_kupon" name="kode_kupon"
                                       value="{{ old('kode_kupon') }}" placeholder="Masukkan kode kupon bila ada">
                                <div class="hint">Kode kupon akan memotong total tagihan sesuai nilai potongan.</div>
                                @error('kode_kupon') <div class="error">{{ $message }}</div> @enderror
                            </div>

                            <div class="field full">
                                <label for="catatan">Catatan Tambahan <span class="opt">(opsional)</span></label>
                                <textarea id="catatan" name="catatan" placeholder="Contoh: kebutuhan khusus, titik penjemputan, atau permintaan lain.">{{ old('catatan') }}</textarea>
                                @error('catatan') <div class="error">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('paket.show', $paket) }}" class="btn btn-outline">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan &amp; Lanjut ke Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>

            <aside class="panel summary">
                @php
                    $summaryStyle = $paket->foto
                        ? "background-image: linear-gradient(180deg, rgba(8,33,31,.1), rgba(8,33,31,.75)), url('".asset('storage/'.$paket->foto)."'); background-size: cover; background-position: center;"
                        : null;
                @endphp
                <div class="summary-media" @if ($summaryStyle) style="{{ $summaryStyle }}" @endif>
                    <span>{{ $paket->destinasi }}</span>
                </div>
                <div class="summary-body">
                    <h2>{{ $paket->judul }}</h2>
                    <div class="dest">{{ $paket->bus ?: 'Transportasi menyesuaikan' }}</div>

                    <ul class="summary-list">
                        <li><span>Harga / orang</span><span>Rp {{ number_format((float) $paket->harga, 0, ',', '.') }}</span></li>
                        <li><span>Jumlah peserta</span><span id="summaryPax">1 orang</span></li>
                    </ul>

                    <div class="total-row">
                        <span class="label">Perkiraan total</span>
                        <span class="value" id="summaryTotal">Rp {{ number_format((float) $paket->harga, 0, ',', '.') }}</span>
                    </div>

                    <div class="note-box">
                        Total belum termasuk potongan kupon. Nilai akhir akan dihitung otomatis oleh sistem saat pesanan disimpan.
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        const harga = {{ (float) $paket->harga }};
        const paxInput = document.getElementById('jumlah_pax');
        const paxLabel = document.getElementById('summaryPax');
        const totalLabel = document.getElementById('summaryTotal');

        function formatRupiah(value) {
            return 'Rp ' + Math.round(value).toLocaleString('id-ID');
        }

        function updateSummary() {
            const pax = Math.max(1, parseInt(paxInput.value, 10) || 1);
            paxLabel.textContent = pax + ' orang';
            totalLabel.textContent = formatRupiah(harga * pax);
        }

        if (paxInput) {
            paxInput.addEventListener('input', updateSummary);
            updateSummary();
        }
    })();
</script>
@endpush
