@extends('layouts.admin')

@section('title', 'Paket Wisata')
@section('breadcrumb', 'Admin / Paket Wisata')

@section('content')
    <div class="card">
        <div class="card-head">
            <div>
                <h2>Daftar Paket Wisata</h2>
                <p>Total {{ $paket->total() }} paket wisata terdaftar dalam sistem.</p>
            </div>
            <a href="{{ route('admin.paket.create') }}" class="btn btn-primary">+ Tambah Paket</a>
        </div>

        @if ($paket->isEmpty())
            <div class="empty">
                Belum ada paket wisata. Klik <strong>+ Tambah Paket</strong> untuk membuat paket pertama.
            </div>
        @else
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Foto</th>
                            <th>Paket Wisata</th>
                            <th>Transportasi</th>
                            <th>Fasilitas</th>
                            <th>Harga</th>
                            <th style="width: 140px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paket as $item)
                            <tr>
                                <td>
                                    @if ($item->foto)
                                        <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->judul }}" class="thumb">
                                    @else
                                        <div class="thumb-empty">No foto</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="cell-title" style="font-weight: 700; color: #222;">{{ $item->judul }}</div>
                                    <div class="cell-sub">📍 {{ $item->destinasi }}</div>
                                </td>
                                <td>
                                    <span style="background: #f7f7f7; border: 1px solid #ebebeb; padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 600; color: #333;">
                                        🚌 {{ $item->bus ?: 'Standard' }}
                                    </span>
                                </td>
                                <td style="max-width: 260px; font-size: 12.5px; color: #555; line-height: 1.4;">
                                    {{ \Illuminate\Support\Str::limit($item->fasilitas, 75) }}
                                </td>
                                <td class="money" style="font-size: 14.5px;">
                                    Rp {{ number_format((float) $item->harga, 0, ',', '.') }}
                                    <small style="display: block; font-size: 11px; font-weight: 600; color: #717171;">/pax</small>
                                </td>
                                <td>
                                    <div class="actions" style="justify-content: center;">
                                        <a href="{{ route('admin.paket.edit', $item) }}" class="btn btn-outline btn-sm">Edit</a>
                                        <form action="{{ route('admin.paket.destroy', $item) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus paket &quot;{{ $item->judul }}&quot;?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-body" style="padding-top:0;">
                {{ $paket->links('pagination.simple') }}
            </div>
        @endif
    </div>
@endsection
