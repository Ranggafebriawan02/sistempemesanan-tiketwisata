@extends('layouts.bendahara')

@section('title', 'Detail Pemesanan')
@section('breadcrumb', 'Bendahara / Manajemen Pemesanan / Detail')

@push('styles')
<style>
    .btn-reject { background: #dc2626; color: #fff; box-shadow: 0 8px 18px -10px rgba(220, 38, 38, .8); }
    .btn-reject:hover { background: #b91c1c; }
    .back-link { display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; font-weight: 700; color: var(--teal-700); text-decoration: none; margin-bottom: 16px; }
    .back-link:hover { text-decoration: underline; }
    .history-item { border: 1px solid var(--slate-200); border-radius: 12px; padding: 15px 16px; margin-bottom: 12px; background: #fff; }
    .history-item.is-latest { border-color: #99f6e4; background: #fbfffe; }
    .history-item.rejected { border-color: #fecaca; background: #fff7f7; }
    .history-top { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
    .history-amount { font-size: 15px; font-weight: 800; color: var(--teal-800); }
    .history-meta { display: flex; flex-wrap: wrap; gap: 4px 16px; margin-top: 7px; font-size: 12px; color: var(--slate-500); }
    .history-note { margin-top: 9px; padding: 9px 11px; border-radius: 8px; background: var(--slate-50); font-size: 12px; color: var(--slate-700); }
    .history-item.rejected .history-note { background: #fee2e2; color: #991b1b; }
    .history-proof { margin-top: 11px; }
    .divider { border: 0; border-top: 1px dashed var(--slate-200); margin: 18px 0; }
    .validation-note { font-size: 11.5px; color: var(--slate-500); margin-top: 8px; line-height: 1.6; }
</style>
@endpush

@section('content')
    @php
        $transaksi = $pemesanan->transaksi->sortByDesc('id');
        $latest = $transaksi->first();
        $status = $pemesanan->statusPembayaran();
    @endphp

    <a href="{{ route('bendahara.pemesanan.index') }}" class="back-link">&larr; Kembali ke daftar pemesanan</a>

    <div class="detail-columns">
        <div>
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-head">
                    <div>
                        <h2>Pemesanan {{ $pemesanan->kode_pemesanan }}</h2>
                        <p>Dibuat {{ $pemesanan->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                    </div>
                    <span class="status status-{{ $status }}">{{ $pemesanan->labelStatus() }}</span>
                </div>
                <div class="card-body">
                    <div class="detail-list">
                        <div>
                            <span class="label">Nama Pemesan</span>
                            <span class="value">{{ $pemesanan->user->nama ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="label">Username</span>
                            <span class="value">{{ $pemesanan->user->username ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="label">Paket Wisata</span>
                            <span class="value">{{ $pemesanan->paket->judul ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="label">Destinasi</span>
                            <span class="value">{{ $pemesanan->paket->destinasi ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="label">Tanggal Berangkat</span>
                            <span class="value">{{ $pemesanan->tanggal_berangkat->translatedFormat('d F Y') }}</span>
                        </div>
                        <div>
                            <span class="label">Jumlah Peserta</span>
                            <span class="value">{{ $pemesanan->jumlah_pax }} orang</span>
                        </div>
                        <div>
                            <span class="label">Nama Rombongan</span>
                            <span class="value">{{ $pemesanan->nama_rombongan ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="label">Nomor HP</span>
                            <span class="value">{{ $pemesanan->no_hp }}</span>
                        </div>
                        @if ($pemesanan->kupon)
                            <div>
                                <span class="label">Kupon</span>
                                <span class="value">{{ $pemesanan->kupon->kode }} (&minus; Rp {{ number_format((float) $pemesanan->kupon->potongan, 0, ',', '.') }})</span>
                            </div>
                        @endif
                        <div>
                            <span class="label">Total Tagihan</span>
                            <span class="value money">Rp {{ number_format((float) $pemesanan->harga, 0, ',', '.') }}</span>
                        </div>
                        @if ($pemesanan->catatan)
                            <div class="full">
                                <span class="label">Catatan Konsumen</span>
                                <p class="value">{{ $pemesanan->catatan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-head">
                    <div>
                        <h2>Riwayat Pembayaran</h2>
                        <p>{{ $transaksi->count() }} bukti pembayaran diunggah konsumen.</p>
                    </div>
                </div>
                <div class="card-body">
                    @if ($transaksi->isEmpty())
                        <div class="empty" style="padding: 30px 10px;">
                            Konsumen belum mengunggah bukti pembayaran untuk pesanan ini.
                        </div>
                    @else
                        @foreach ($transaksi as $trx)
                            @php
                                $ext = strtolower(pathinfo($trx->bukti_pembayaran ?? '', PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true);
                            @endphp
                            <div class="history-item {{ $loop->first ? 'is-latest' : '' }} {{ $trx->status === 'ditolak' ? 'rejected' : '' }}">
                                <div class="history-top">
                                    <div>
                                        <span class="status status-{{ $trx->status }}">{{ ucfirst($trx->status) }}</span>
                                        @if ($loop->first)
                                            <span class="badge" style="margin-left:6px;">Terbaru</span>
                                        @endif
                                    </div>
                                    <span class="history-amount">Rp {{ number_format((float) $trx->jumlah_bayar, 0, ',', '.') }}</span>
                                </div>

                                <div class="history-meta">
                                    <span>Metode: <strong style="color:var(--slate-700);">{{ ucfirst($trx->alat_pembayaran) }}</strong></span>
                                    <span>Tanggal bayar: <strong style="color:var(--slate-700);">{{ $trx->tanggal_bayar?->translatedFormat('d M Y') ?? '-' }}</strong></span>
                                    <span>Diunggah: <strong style="color:var(--slate-700);">{{ $trx->created_at->translatedFormat('d M Y H:i') }}</strong></span>
                                </div>

                                @if ($trx->catatan)
                                    <div class="history-note">
                                        <strong>Catatan:</strong> {{ $trx->catatan }}
                                    </div>
                                @endif

                                @if ($trx->bukti_pembayaran)
                                    <div class="history-proof">
                                        <div class="proof-box">
                                            @if ($isImage)
                                                <a href="{{ asset('storage/'.$trx->bukti_pembayaran) }}" target="_blank" rel="noopener">
                                                    <img src="{{ asset('storage/'.$trx->bukti_pembayaran) }}" alt="Bukti pembayaran {{ $pemesanan->kode_pemesanan }}">
                                                </a>
                                            @endif
                                            <a href="{{ asset('storage/'.$trx->bukti_pembayaran) }}" target="_blank" rel="noopener" class="proof-file" style="margin-top:10px;">
                                                &#128196; Buka bukti pembayaran{{ $isImage ? '' : ' (PDF)' }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <aside>
            <div class="card" style="margin-bottom: 18px;">
                <div class="card-head">
                    <div>
                        <h2>Ringkasan</h2>
                        <p>Status pembayaran saat ini.</p>
                    </div>
                </div>
                <div class="card-body">
                    <div style="text-align:center; padding: 6px 0 14px;">
                        <span class="status status-{{ $status }}">{{ $pemesanan->labelStatus() }}</span>
                        <div style="font-size: 26px; font-weight: 800; color: var(--teal-800); letter-spacing: -.03em; margin-top: 10px;">
                            Rp {{ number_format((float) $pemesanan->harga, 0, ',', '.') }}
                        </div>
                        <div style="font-size: 11.5px; color: var(--slate-500);">Total tagihan</div>
                    </div>

                    @if ($latest)
                        <hr class="divider">
                        <div class="detail-list" style="grid-template-columns: 1fr;">
                            <div>
                                <span class="label">Jumlah dibayar</span>
                                <span class="value">Rp {{ number_format((float) $latest->jumlah_bayar, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="label">Metode</span>
                                <span class="value">{{ ucfirst($latest->alat_pembayaran) }}</span>
                            </div>
                        </div>
                    @endif

                    @if ($latest && $latest->status === 'valid')
                        <a href="{{ route('bendahara.pembayaran.kuitansi', $latest) }}" class="btn btn-primary btn-block" style="margin-top: 16px;">
                            Cetak Kuitansi
                        </a>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-head">
                    <div>
                        <h2>Validasi Pembayaran</h2>
                        <p>Periksa bukti sebelum memutuskan.</p>
                    </div>
                </div>
                <div class="card-body">
                    @if (! $latest)
                        <div class="empty" style="padding: 22px 6px;">
                            Belum ada pembayaran yang dapat divalidasi.
                        </div>
                    @elseif ($latest->status === 'menunggu')
                        <div class="decision-box">
                            <h3>Pembayaran menunggu validasi</h3>
                            <p>Nominal Rp {{ number_format((float) $latest->jumlah_bayar, 0, ',', '.') }} via {{ ucfirst($latest->alat_pembayaran) }}.</p>

                            <form action="{{ route('bendahara.pembayaran.validasi', $latest) }}" method="POST" style="margin-top: 14px;">
                                @csrf

                                <div class="field" style="margin-bottom: 12px;">
                                    <label for="catatanValidasi">Catatan Validasi</label>
                                    <textarea id="catatanValidasi" name="catatan" placeholder="Wajib diisi jika pembayaran ditolak..." style="min-height: 78px;">{{ old('catatan') }}</textarea>
                                    @error('catatan') <div class="error">{{ $message }}</div> @enderror
                                    @error('keputusan') <div class="error">{{ $message }}</div> @enderror
                                </div>

                                <div class="decision-actions">
                                    <button type="submit" name="keputusan" value="valid" class="btn btn-primary">Terima</button>
                                    <button type="submit" name="keputusan" value="ditolak" class="btn btn-reject" id="btnTolak">Tolak</button>
                                </div>
                            </form>

                            <p class="validation-note">
                                Menolak pembayaran akan membuka kesempatan konsumen untuk mengunggah ulang bukti.
                            </p>
                        </div>
                    @elseif ($latest->status === 'valid')
                        <div class="empty" style="padding: 22px 6px;">
                            Pembayaran sudah <strong>divalidasi</strong>. Kuitansi dapat dicetak melalui tombol di atas.
                        </div>
                    @else
                        <div class="empty" style="padding: 22px 6px;">
                            Pembayaran terakhir <strong>ditolak</strong>. Menunggu konsumen mengunggah ulang bukti pembayaran.
                        </div>
                    @endif
                </div>
            </div>
        </aside>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        const reject = document.getElementById('btnTolak');
        const catatan = document.getElementById('catatanValidasi');

        if (reject && catatan) {
            reject.addEventListener('click', function (event) {
                if (catatan.value.trim() === '') {
                    event.preventDefault();
                    catatan.focus();
                    catatan.style.borderColor = '#dc2626';
                    alert('Catatan wajib diisi saat menolak pembayaran.');
                }
            });
        }
    })();
</script>
@endpush
