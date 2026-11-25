@extends('layouts.admin')

@section('title', 'Detail Pemesanan '.$pemesanan->kode_pemesanan)
@section('breadcrumb', 'Admin / Data Pemesanan / Detail')

@section('content')
    @php
        $status = $pemesanan->statusPembayaran();
        $trxTerakhir = $pemesanan->transaksi->sortByDesc('created_at')->first();
    @endphp

    <div style="display:grid; grid-template-columns: 1fr 360px; gap: 24px; align-items: start;">
        <div>
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-head">
                    <div>
                        <h2>Informasi Pemesan &amp; Paket</h2>
                        <p>Dibuat pada {{ $pemesanan->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                    </div>
                    <span class="status status-{{ $status }}">{{ $pemesanan->labelStatus() }}</span>
                </div>
                <div class="card-body">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 16px 24px;">
                        <div>
                            <small style="color:#717171; display:block;">Kode Pemesanan</small>
                            <strong style="font-size:15px;">{{ $pemesanan->kode_pemesanan }}</strong>
                        </div>
                        <div>
                            <small style="color:#717171; display:block;">Nama Pemesan / Akun</small>
                            <strong>{{ $pemesanan->user->nama ?? '-' }} ({{ $pemesanan->user->username ?? '-' }})</strong>
                        </div>
                        <div>
                            <small style="color:#717171; display:block;">Paket Wisata</small>
                            <strong>{{ $pemesanan->paket->judul ?? '-' }}</strong>
                            <small style="display:block; color:#717171;">Destinasi: {{ $pemesanan->paket->destinasi ?? '-' }}</small>
                        </div>
                        <div>
                            <small style="color:#717171; display:block;">Tanggal Keberangkatan</small>
                            <strong>{{ $pemesanan->tanggal_berangkat->translatedFormat('l, d F Y') }}</strong>
                        </div>
                        <div>
                            <small style="color:#717171; display:block;">Jumlah Peserta</small>
                            <strong>{{ $pemesanan->jumlah_pax }} orang</strong>
                        </div>
                        <div>
                            <small style="color:#717171; display:block;">Nomor Telepon / WhatsApp</small>
                            <strong>{{ $pemesanan->no_hp }}</strong>
                        </div>
                        <div>
                            <small style="color:#717171; display:block;">Nama Rombongan</small>
                            <strong>{{ $pemesanan->nama_rombongan ?: '-' }}</strong>
                        </div>
                        <div>
                            <small style="color:#717171; display:block;">Total Tagihan</small>
                            <strong style="color:#222222; font-size:16px;">Rp {{ number_format((float) $pemesanan->harga, 0, ',', '.') }}</strong>
                        </div>
                        @if ($pemesanan->catatan)
                            <div style="grid-column: 1 / -1;">
                                <small style="color:#717171; display:block;">Catatan Pemesan</small>
                                <p style="font-size:13px; color:#333333; margin-top:4px;">{{ $pemesanan->catatan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-head">
                    <div>
                        <h2>Riwayat Pembayaran &amp; Bukti Transfer</h2>
                        <p>Catatan transaksi untuk pemesanan ini</p>
                    </div>
                </div>
                <div class="card-body">
                    @if ($pemesanan->transaksi->isEmpty())
                        <p style="color:#717171; font-size:13px;">Belum ada pembayaran atau bukti transfer yang diunggah.</p>
                    @else
                        <div style="display:grid; gap:14px;">
                            @foreach ($pemesanan->transaksi->sortByDesc('created_at') as $trx)
                                <div style="border: 1px solid #ebebeb; border-radius: 12px; padding: 16px; background: #fff;">
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                                        <span class="status status-{{ $trx->status }}">{{ ucfirst($trx->status) }}</span>
                                        <strong style="font-size:15px; color:#222222;">Rp {{ number_format((float) $trx->jumlah_bayar, 0, ',', '.') }}</strong>
                                    </div>
                                    <div style="font-size:12.5px; color:#555555; display:grid; gap:4px;">
                                        <div>Metode: <strong>{{ ucfirst($trx->alat_pembayaran) }}</strong></div>
                                        <div>Tanggal Bayar: <strong>{{ $trx->tanggal_bayar?->translatedFormat('d M Y') ?? '-' }}</strong></div>
                                        <div>Waktu Unggah: <strong>{{ $trx->created_at->translatedFormat('d M Y, H:i') }} WIB</strong></div>
                                    </div>
                                    @if ($trx->catatan)
                                        <div style="margin-top:8px; padding:8px 10px; background:#f7f7f7; border-radius:6px; font-size:12px; color:#333333;">
                                            <strong>Catatan:</strong> {{ $trx->catatan }}
                                        </div>
                                    @endif
                                    @if ($trx->bukti_pembayaran)
                                        <div style="margin-top:10px;">
                                            <a href="{{ asset('storage/'.$trx->bukti_pembayaran) }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                                                Lihat Berkas Bukti Transfer
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <aside class="card">
            <div class="card-head">
                <h2>Aksi</h2>
            </div>
            <div class="card-body" style="display:grid; gap:10px;">
                <a href="{{ route('admin.pemesanan.index') }}" class="btn btn-outline btn-block">Kembali ke Daftar</a>
                <a href="{{ route('admin.user.edit', $pemesanan->user_id) }}" class="btn btn-outline btn-block">Kelola Akun Pemesan</a>
            </div>
        </aside>
    </div>
@endsection

