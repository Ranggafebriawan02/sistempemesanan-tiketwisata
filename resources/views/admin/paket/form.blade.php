@extends('layouts.admin')

@section('title', isset($paket) ? 'Edit Paket Wisata' : 'Tambah Paket Wisata')
@section('breadcrumb', 'Admin / Paket Wisata / ' . (isset($paket) ? 'Edit' : 'Tambah'))

@section('content')
    <div class="card" style="max-width:860px;">
        <div class="card-head">
            <div>
                <h2>{{ isset($paket) ? 'Edit Paket Wisata' : 'Tambah Paket Wisata' }}</h2>
                <p>{{ isset($paket) ? 'Perbarui informasi paket yang sudah ada.' : 'Lengkapi informasi paket wisata baru.' }}</p>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ isset($paket) ? route('admin.paket.update', $paket) : route('admin.paket.store') }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($paket))
                    @method('PUT')
                @endif

                <div class="form-grid">
                    <div class="field">
                        <label for="judul">Judul Paket</label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul', $paket->judul ?? '') }}" placeholder="Contoh: Ziarah Wali Songo 7 Hari">
                        @error('judul') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="destinasi">Destinasi</label>
                        <input type="text" id="destinasi" name="destinasi" value="{{ old('destinasi', $paket->destinasi ?? '') }}" placeholder="Contoh: Jawa Timur & Jawa Tengah">
                        @error('destinasi') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="bus">Transportasi / Bus</label>
                        <input type="text" id="bus" name="bus" value="{{ old('bus', $paket->bus ?? '') }}" placeholder="Contoh: Bus Executive">
                        @error('bus') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="harga">Harga per Orang (Rp)</label>
                        <input type="number" id="harga" name="harga" min="0" step="1000" value="{{ old('harga', $paket->harga ?? '') }}" placeholder="Contoh: 3500000">
                        @error('harga') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field full">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" placeholder="Jelaskan rute, durasi, dan keunggulan paket...">{{ old('deskripsi', $paket->deskripsi ?? '') }}</textarea>
                        @error('deskripsi') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field full">
                        <label for="fasilitas">Fasilitas</label>
                        <textarea id="fasilitas" name="fasilitas" placeholder="Pisahkan dengan koma. Contoh: Bus AC, penginapan, makan 3x sehari, pemandu wisata">{{ old('fasilitas', $paket->fasilitas ?? '') }}</textarea>
                        <div class="hint">Gunakan tanda koma (,) untuk memisahkan setiap fasilitas.</div>
                        @error('fasilitas') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field full">
                        <label for="foto">Foto Paket</label>
                        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp">
                        <div class="hint">Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.</div>
                        @error('foto') <div class="error">{{ $message }}</div> @enderror

                        @if (isset($paket) && $paket->foto)
                            <div class="current-photo">
                                <img src="{{ asset('storage/'.$paket->foto) }}" alt="{{ $paket->judul }}">
                                <span>Foto saat ini. Unggah file baru untuk menggantinya.</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.paket.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">{{ isset($paket) ? 'Simpan Perubahan' : 'Simpan Paket' }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
