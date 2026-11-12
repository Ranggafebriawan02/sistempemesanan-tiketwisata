@extends('layouts.app')

@section('title', 'Masuk — PT. Puma Star Jaya')

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
        max-width: 440px;
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

    .field { margin-bottom: 16px; }
    .field label {
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--slate-700);
        margin-bottom: 6px;
    }
    .field input {
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
    .field input:focus {
        outline: none;
        border-color: var(--teal-600);
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .14);
    }
    .field .error { margin-top: 5px; font-size: 12px; color: var(--danger); font-weight: 600; }
    .field-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin: 4px 0 18px; }
    .check { display: inline-flex; align-items: center; gap: 8px; font-size: 12.5px; color: var(--slate-700); font-weight: 600; }
    .check input { width: 15px; height: 15px; accent-color: var(--teal-700); }
    .auth-alt { margin-top: 20px; text-align: center; font-size: 13px; color: var(--slate-500); }
    .auth-alt a { color: var(--teal-700); font-weight: 700; text-decoration: none; }
    .auth-alt a:hover { text-decoration: underline; }

    .demo-box {
        margin-top: 22px;
        background: var(--slate-50);
        border: 1px dashed var(--slate-300);
        border-radius: 12px;
        padding: 14px;
    }
    .demo-box h4 { font-size: 11.5px; text-transform: uppercase; letter-spacing: .08em; color: var(--slate-500); margin-bottom: 6px; }
    .demo-desc { font-size: 12px; color: var(--slate-500); margin-bottom: 12px; }
    .quick-roles {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }
    .btn-quick-role {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 10px 12px;
        background: #fff;
        border: 1px solid var(--slate-200);
        border-radius: 10px;
        cursor: pointer;
        text-align: left;
        transition: all .2s;
    }
    .btn-quick-role:hover {
        border-color: var(--teal-600);
        background: var(--teal-50);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px -2px rgba(13, 148, 136, .2);
    }
    .btn-quick-role strong {
        font-size: 12.5px;
        color: var(--slate-900);
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .btn-quick-role span {
        font-size: 11px;
        color: var(--slate-500);
        margin-top: 2px;
    }
    .btn-quick-role:active {
        transform: translateY(0);
    }
</style>
@endpush

@section('content')
    <div class="auth-wrap">
        <div class="auth-card">
            <div class="auth-head">
                <span class="badge">PSJ</span>
                <h1>Masuk ke Akun</h1>
                <p>Gunakan username dan password yang terdaftar.</p>
            </div>

            <!-- Tombol Cepat Pilihan Akun -->
            <div class="demo-box" style="margin-top: 0; margin-bottom: 22px;">
                <h4>Pilih Cepat Akun</h4>
                <p class="demo-desc">Klik salah satu akun untuk langsung mengisi username &amp; password:</p>
                <div class="quick-roles">
                    <button type="button" class="btn-quick-role" onclick="pilihAkun('admin')">
                        <strong>🛠️ Admin</strong>
                        <span>Kelola master &amp; user</span>
                    </button>
                    <button type="button" class="btn-quick-role" onclick="pilihAkun('bendahara')">
                        <strong>💰 Bendahara</strong>
                        <span>Validasi pembayaran</span>
                    </button>
                    <button type="button" class="btn-quick-role" onclick="pilihAkun('direktur')">
                        <strong>👔 Direktur</strong>
                        <span>Pantau &amp; cetak laporan</span>
                    </button>
                    <button type="button" class="btn-quick-role" onclick="pilihAkun('konsumen')">
                        <strong>🧳 Konsumen</strong>
                        <span>Pesan &amp; bayar wisata</span>
                    </button>
                </div>
            </div>

            <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                @csrf

                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username" autocomplete="username" autofocus>
                    @error('username') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" autocomplete="current-password">
                    @error('password') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field-row">
                    <label class="check">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Masuk</button>
            </form>

            <p class="auth-alt">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
        </div>
    </div>

    <script>
        function pilihAkun(role) {
            const userInput = document.getElementById('username');
            const passInput = document.getElementById('password');

            userInput.value = role;
            passInput.value = 'password';

            // Beri highlight animasi halus pada input
            userInput.style.transition = 'background-color 0.3s, border-color 0.3s';
            passInput.style.transition = 'background-color 0.3s, border-color 0.3s';
            
            userInput.style.backgroundColor = '#ccfbf1';
            passInput.style.backgroundColor = '#ccfbf1';
            userInput.style.borderColor = '#0d9488';
            passInput.style.borderColor = '#0d9488';

            setTimeout(() => {
                userInput.style.backgroundColor = '';
                passInput.style.backgroundColor = '';
                userInput.style.borderColor = '';
                passInput.style.borderColor = '';
            }, 500);
        }
    </script>
@endsection
