@if ($pemesanan->isEmpty())
    <div class="empty">
        Tidak ada data pemesanan yang sesuai dengan filter atau kata kunci pencarian.
    </div>
@else
    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr>
                    <th style="width: 140px;">Kode Pesanan</th>
                    <th>Pemesan</th>
                    <th>Paket Wisata</th>
                    <th style="width: 110px;">Keberangkatan</th>
                    <th style="width: 130px;">Total Tagihan</th>
                    <th style="width: 130px; text-align: center;">Status</th>
                    <th style="width: 80px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pemesanan as $item)
                    @php $st = $item->statusPembayaran(); @endphp
                    <tr>
                        <td>
                            <div class="cell-title" style="font-weight: 800; color: #222;">{{ $item->kode_pemesanan }}</div>
                            <div class="cell-sub">{{ $item->created_at->translatedFormat('d M Y, H:i') }}</div>
                        </td>
                        <td>
                            <div class="cell-title">{{ $item->user->nama ?? '-' }}</div>
                            <div class="cell-sub">📞 {{ $item->no_hp }}</div>
                        </td>
                        <td>
                            <div class="cell-title">{{ $item->paket->judul ?? '-' }}</div>
                            <div class="cell-sub">
                                {{ $item->jumlah_pax }} peserta
                                @if ($item->nama_rombongan)
                                    &middot; <span style="color:#555;">{{ $item->nama_rombongan }}</span>
                                @endif
                            </div>
                        </td>
                        <td style="white-space: nowrap;">
                            {{ $item->tanggal_berangkat->translatedFormat('d M Y') }}
                        </td>
                        <td class="money">
                            Rp {{ number_format((float) $item->harga, 0, ',', '.') }}
                        </td>
                        <td style="text-align: center;">
                            <span class="status status-{{ $st }}">{{ $item->labelStatus() }}</span>
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('bendahara.pemesanan.show', $item) }}" class="btn btn-outline btn-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
