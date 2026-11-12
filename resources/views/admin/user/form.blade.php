@extends('layouts.admin')

@section('title', isset($user) ? 'Edit User' : 'Tambah User')
@section('breadcrumb', 'Admin / Manajemen User / ' . (isset($user) ? 'Edit' : 'Tambah'))

@section('content')
    @php
        $levelLabels = \App\Models\User::LEVEL_LABELS;
    @endphp

    <div class="card" style="max-width:860px;">
        <div class="card-head">
            <div>
                <h2>{{ isset($user) ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru' }}</h2>
                <p>{{ isset($user) ? 'Perbarui data akun, level akses, atau status aktifnya.' : 'Buat akun baru untuk staff maupun konsumen.' }}</p>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ isset($user) ? route('admin.user.update', $user) : route('admin.user.store') }}"
                  method="POST">
                @csrf
                @if (isset($user))
                    @method('PUT')
                @endif

                <div class="form-grid">
                    <div class="field">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username ?? '') }}" placeholder="Contoh: admin2" autocomplete="off">
                        <div class="hint">Hanya huruf, angka, tanda hubung, dan garis bawah.</div>
                        @error('username') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama ?? '') }}" placeholder="Contoh: Budi Santoso">
                        @error('nama') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" placeholder="nama@perusahaan.com">
                        @error('email') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="level">Level Akses</label>
                        <select id="level" name="level">
                            @foreach ($levelLabels as $value => $label)
                                <option value="{{ $value }}" @selected(old('level', $user->level ?? \App\Models\User::ROLE_KONSUMEN) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('level') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="no_hp">Nomor HP</label>
                        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp ?? '') }}" placeholder="081234567890">
                        @error('no_hp') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field full">
                        <label for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat" placeholder="Alamat lengkap pengguna (opsional)">{{ old('alamat', $user->alamat ?? '') }}</textarea>
                        @error('alamat') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" autocomplete="new-password" {{ isset($user) ? '' : 'required' }}>
                        <div class="hint">
                            {{ isset($user) ? 'Kosongkan jika tidak ingin mengubah password.' : 'Minimal 6 karakter.' }}
                        </div>
                        @error('password') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" {{ isset($user) ? '' : 'required' }}>
                        @error('password_confirmation') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field full">
                        <label for="aktif">Status Akun</label>
                        <label class="checkbox-field" for="aktif">
                            <input type="hidden" name="aktif" value="0">
                            <input type="checkbox" id="aktif" name="aktif" value="1" @checked(old('aktif', $user->aktif ?? true))>
                            <span class="text">
                                <strong>Akun aktif</strong>
                                <span>Akun yang dinonaktifkan tidak dapat login ke sistem sampai diaktifkan kembali.</span>
                            </span>
                        </label>
                        @error('aktif') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.user.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Simpan Perubahan' : 'Simpan Pengguna' }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
