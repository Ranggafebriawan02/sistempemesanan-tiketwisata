@extends('layouts.admin')

@section('title', 'Paket Wisata')
@section('breadcrumb', 'Admin / Paket Wisata')

@section('content')
    <div class="card">
        <div class="card-head">
            <div>
                <h2>Daftar Paket Wisata</h2>
                <p>Total {{ $paket->total() }} paket terdaftar.</p>
            </div>
            <a href="{{ route('admin.paket.create') }}" class="btn btn-primary">+ Tambah Paket</a>
        </div>

        @if ($paket->isEmpty())
            <div class="empty">
                Belum ada paket wisata. Klik <strong>Tambah Paket</strong> untuk membuat paket pertama.
            </div>
        @else
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th style="width:80px;">Foto</th>
                            <th>Paket</th>
                            <th>Transportasi</th>
                            <th>Fasilitas</th>
                            <th>Harga</th>
                            <th style="width:130px;">Aksi</th>
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
                                    <div class="cell-title">{{ $item->judul }}</div>
                                    <div class="cell-sub">{{ $item->destinasi }}</div>
                                </td>
                                <td>{{ $item->bus ?: '-' }}</td>
                                <td style="max-width:260px;">{{ \Illuminate\Support\Str::limit($item->fasilitas, 60) }}</td>
                                <td class="money">Rp {{ number_format((float) $item->harga, 0, ',', '.') }}</td>
                                <td>
                                    <div class="actions">
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
