@extends('layouts.app')

@section('title', 'Pesan '.$paket->judul.' — PT. Puma Star Jaya')

@push('styles')
<style>
    .booking-page { padding: 34px 0 64px; }
    .breadcrumb { font-size: 12.5px; color: var(--slate-500); margin-bottom: 18px; }
    .breadcrumb a { color: var(--teal-700); font-weight: 600; text-decoration: none; }
    .breadcrumb a:hover { text-decoration: underline; }

    .booking-grid { display: grid; grid-template-columns: 1fr 370px; gap: 28px; align-items: start; }

    .panel { background: #fff; border: 1px solid var(--slate-200); border-radius: 16px; box-shadow: var(--shadow-sm); }
    .panel-head { padding: 22px 24px; border-bottom: 1px solid var(--slate-200); }
    .panel-head h1 { font-size: 20px; letter-spacing: -.02em; }
    .panel-head p { margin-top: 5px; font-size: 13px; color: var(--slate-500); }
    .panel-body { padding: 24px; }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 18px; }
    .field { margin-bottom: 17px; }
    .field.full { grid-column: 1 / -1; }
    .field label { display: block; font-size: 12.5px; font-weight: 700; color: var(--slate-700); margin-bottom: 6px; }
    .field label .opt { font-weight: 500; color: var(--slate-500); font-size: 11px; }
    .field input, .field textarea, .field select {
        width: 100%; border: 1px solid var(--slate-300); border-radius: 10px;
        padding: 11px 13px; font-family: inherit; font-size: 13.5px; color: var(--slate-900);
        background: #fff; transition: border-color .18s, box-shadow .18s;
    }
    .field textarea { resize: vertical; min-height: 92px; }
    .field input:focus, .field textarea:focus, .field select:focus {
        outline: none; border-color: var(--teal-600); box-shadow: 0 0 0 3px rgba(13, 148, 136, .14);
    }
    .field .error { margin-top: 5px; font-size: 12px; color: var(--danger); font-weight: 600; }
    .field .hint { margin-top: 5px; font-size: 11.5px; color: var(--slate-500); }

    .summary { position: sticky; top: 96px; overflow: hidden; }
    .summary-media {
        height: 120px; display: flex; align-items: flex-end; padding: 16px;
        color: #fff; position: relative;
        background: linear-gradient(135deg, #0f766e, #134e4a);
    }
    .summary-media::after { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, transparent 10%, rgba(8, 33, 31, .7)); }
    .summary-media span { position: relative; z-index: 1; font-size: 11.5px; font-weight: 700; background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.3); padding: 4px 10px; border-radius: 999px; }
    .summary-body { padding: 20px; }
    .summary-body h2 { font-size: 16px; line-height: 1.35; }
    .summary-body .dest { font-size: 12px; color: var(--slate-500); margin-top: 4px; }
    .summary-list { list-style: none; display: grid; gap: 10px; margin: 16px 0; padding: 16px 0; border-top: 1px dashed var(--slate-200); border-bottom: 1px dashed var(--slate-200); }
    .summary-list li { display: flex; justify-content: space-between; gap: 12px; font-size: 12.5px; }
    .summary-list span:first-child { color: var(--slate-500); }
    .summary-list span:last-child { font-weight: 700; text-align: right; }
    .total-row { display: flex; justify-content: space-between; align-items: baseline; gap: 12px; }
    .total-row .label { font-size: 12px; font-weight: 600; color: var(--slate-500); }
    .total-row .value { font-size: 23px; font-weight: 800; color: var(--teal-800); letter-spacing: -.03em; }
    .note-box { margin-top: 16px; background: var(--teal-50); border: 1px solid #99f6e4; border-radius: 10px; padding: 11px 13px; font-size: 11.5px; color: var(--teal-800); line-height: 1.6; }

    .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 8px; padding-top: 18px; border-top: 1px solid var(--slate-200); }

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
