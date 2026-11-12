@extends('layouts.app')

@section('title', 'Daftar Akun — PT. Puma Star Jaya')

@push('styles')
<style>
    .auth-wrap {
        min-height: calc(100vh - 180px);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
        margin: 0 auto;
        background:
            radial-gradient(700px 380px at 12% 0%, rgba(13, 148, 136, .12), transparent 60%),
            radial-gradient(600px 320px at 100% 100%, rgba(245, 158, 11, .12), transparent 60%);
    }
    .auth-card {
        width: 100%;
        max-width: 560px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid var(--slate-200);
        border-radius: 18px;
        padding: 34px;
        box-shadow: var(--shadow);
    }
    .auth-head { text-align: center; margin-bottom: 26px; }
    .auth-head .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 46px; height: 46px;
        border-radius: 14px;
        background: var(--teal-50);
        border: 1px solid #99f6e4;
        color: var(--teal-700);
        font-weight: 800;
        font-size: 13px;
        margin-bottom: 12px;
    }
    .auth-head h1 { font-size: 23px; letter-spacing: -.02em; }
    .auth-head p { margin-top: 6px; font-size: 13.5px; color: var(--slate-500); }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px; }
    .field { margin-bottom: 16px; }
    .field.full { grid-column: 1 / -1; }
    .field label {
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--slate-700);
        margin-bottom: 6px;
    }
    .field input, .field textarea {
        width: 100%;
        border: 1px solid var(--slate-300);
        border-radius: 10px;
        padding: 11px 13px;
        font-family: inherit;
        font-size: 13.5px;
        color: var(--slate-900);
        background: var(--white);
        transition: border-color .18s, box-shadow .18s;
    }
    .field textarea { resize: vertical; min-height: 74px; }
    .field input:focus, .field textarea:focus {
        outline: none;
        border-color: var(--teal-600);
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .14);
    }
    .field .error { margin-top: 5px; font-size: 12px; color: var(--danger); font-weight: 600; }
    .auth-alt { margin-top: 20px; text-align: center; font-size: 13px; color: var(--slate-500); }
    .auth-alt a { color: var(--teal-700); font-weight: 700; text-decoration: none; }
    .auth-alt a:hover { text-decoration: underline; }
    .hint { font-size: 11.5px; color: var(--slate-500); margin-top: 4px; }

    @media (max-width: 560px) {
        .form-grid { grid-template-columns: 1fr; }
        .auth-card { padding: 26px 22px; }
    }
</style>
@endpush

@section('content')
    <div class="auth-wrap">
        <div class="auth-card">
            <div class="auth-head">
                <span class="badge">PSJ</span>
                <h1>Daftar Akun Konsumen</h1>
                <p>Lengkapi data di bawah untuk mulai memesan paket wisata.</p>
            </div>

            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                <div class="form-grid">
                    <div class="field">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="contoh: budi01" autocomplete="username" autofocus>
                        @error('username') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Nama sesuai identitas">
                        @error('nama') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email">
                        @error('email') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="no_hp">Nomor HP</label>
                        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
                        @error('no_hp') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field full">
                        <label for="alamat">Alamat (opsional)</label>
                        <textarea id="alamat" name="alamat" placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                        @error('alamat') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" autocomplete="new-password">
                        @error('password') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" autocomplete="new-password">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Daftar Sekarang</button>
            </form>

            <p class="auth-alt">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
        </div>
    </div>
@endsection
