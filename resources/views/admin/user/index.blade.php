@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('breadcrumb', 'Admin / Manajemen User')

@section('content')
    @php
        $levelLabels = \App\Models\User::LEVEL_LABELS;
        $levelFilters = ['semua' => 'Semua'] + $levelLabels;
    @endphp

    <div class="card">
        <div class="card-head">
            <div>
                <h2>Daftar Pengguna</h2>
                <p>Menampilkan {{ $users->total() }} dari {{ $total }} akun terdaftar.</p>
            </div>
            <a href="{{ route('admin.user.create') }}" class="btn btn-primary">+ Tambah User</a>
        </div>

        <div class="card-body" style="padding-bottom: 0;">
            <div class="toolbar-row">
                <div class="filter-tabs">
                    @foreach ($levelFilters as $value => $label)
                        <a href="{{ route('admin.user.index', array_filter(['level' => $value, 'q' => $keyword ?: null])) }}"
                           class="filter-tab {{ $filter['level'] === $value ? 'active' : '' }}">
                            {{ $label }}
                            <span class="count">{{ $value === 'semua' ? $total : ($ringkasan[$value] ?? 0) }}</span>
                        </a>
                    @endforeach
                </div>

                <form action="{{ route('admin.user.index') }}" method="GET" class="search-form">
                    @if ($filter['level'] !== 'semua')
                        <input type="hidden" name="level" value="{{ $filter['level'] }}">
                    @endif
                    <input type="search" name="q" value="{{ $keyword }}" placeholder="Cari nama, username, email...">
                    <button type="submit" class="btn btn-outline btn-sm">Cari</button>
                </form>
            </div>
        </div>

        @if ($users->isEmpty())
            <div class="empty">
                Tidak ada akun yang cocok dengan filter atau kata kunci pencarian.
            </div>
        @else
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Level</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th class="text-right">Pesanan</th>
                            <th style="width: 260px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $item)
                            <tr>
                                <td>
                                    <div class="cell-title">
                                        {{ $item->nama }}
                                        @if ($item->is(auth()->user()))
                                            <span class="badge">Anda</span>
                                        @endif
                                    </div>
                                    <div class="cell-sub">{{ '@'.$item->username }} &middot; {{ $item->email }}</div>
                                </td>
                                <td>
                                    <span class="level-tag level-{{ $item->level }}">{{ $item->labelLevel() }}</span>
                                </td>
                                <td>
                                    <div>{{ $item->no_hp ?: '-' }}</div>
                                    <div class="cell-sub">{{ \Illuminate\Support\Str::limit($item->alamat, 42) ?: 'Alamat belum diisi' }}</div>
                                </td>
                                <td>
                                    <span class="status status-{{ $item->aktif ? 'aktif' : 'nonaktif' }}">
                                        {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="text-right">{{ number_format($item->pemesanan_count, 0, ',', '.') }}</td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.user.edit', $item) }}" class="btn btn-outline btn-sm">Edit</a>

                                        <form action="{{ route('admin.user.reset-password', $item) }}" method="POST"
                                              onsubmit="return confirm('Reset password akun {{ $item->username }} menjadi &quot;password&quot;?');">
                                            @csrf
                                            <button type="submit" class="btn btn-outline btn-sm">Reset PW</button>
                                        </form>

                                        @unless ($item->is(auth()->user()))
                                            <form action="{{ route('admin.user.toggle-aktif', $item) }}" method="POST"
                                                  onsubmit="return confirm('{{ $item->aktif ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $item->username }}?');">
                                                @csrf
                                                <button type="submit" class="btn {{ $item->aktif ? 'btn-danger' : 'btn-primary' }} btn-sm">
                                                    {{ $item->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>

                                            @if ($item->pemesanan_count === 0)
                                                <form action="{{ route('admin.user.destroy', $item) }}" method="POST"
                                                      onsubmit="return confirm('Yakin ingin menghapus akun {{ $item->username }}? Tindakan ini tidak dapat dibatalkan.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                            @endif
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-body" style="padding-top: 0;">
                {{ $users->links('pagination.simple') }}
            </div>
        @endif
    </div>
@endsection
