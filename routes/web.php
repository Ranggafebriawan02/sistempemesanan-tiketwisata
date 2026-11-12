<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\PemesananController as AdminPemesananController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Bendahara\PembayaranController;
use App\Http\Controllers\Konsumen\PemesananController as KonsumenPemesananController;
use App\Http\Controllers\Laporan\LaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/paket-wisata', [HomeController::class, 'paket'])->name('paket.index');
Route::get('/paket-wisata/{paket}', [HomeController::class, 'show'])->name('paket.show');

// Midtrans Notification Webhook
Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle'])->name('midtrans.callback');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/pemesanan', [AdminPemesananController::class, 'index'])->name('pemesanan.index');
        Route::get('/pemesanan/{pemesanan}', [AdminPemesananController::class, 'show'])->name('pemesanan.show');

        Route::resource('paket', PaketController::class)->except('show');

        Route::post('user/{user}/reset-password', [UserController::class, 'resetPassword'])->name('user.reset-password');
        Route::post('user/{user}/toggle-aktif', [UserController::class, 'toggleAktif'])->name('user.toggle-aktif');
        Route::resource('user', UserController::class)->except('show');
    });

Route::middleware(['auth', 'role:bendahara'])
    ->prefix('bendahara')
    ->name('bendahara.')
    ->group(function () {
        Route::get('/pemesanan', [PembayaranController::class, 'index'])->name('pemesanan.index');
        Route::get('/pemesanan/{pemesanan}', [PembayaranController::class, 'show'])->name('pemesanan.show');
        Route::post('/pembayaran/{transaksi}/validasi', [PembayaranController::class, 'validasi'])->name('pembayaran.validasi');
        Route::get('/pembayaran/{transaksi}/kuitansi', [PembayaranController::class, 'kuitansi'])->name('pembayaran.kuitansi');
    });

Route::middleware(['auth', 'role:admin,bendahara,direktur'])
    ->prefix('laporan')
    ->name('laporan.')
    ->group(function () {
        Route::get('/pemesanan', [LaporanController::class, 'pemesanan'])->name('pemesanan');
        Route::get('/pemesanan/cetak', [LaporanController::class, 'cetakPemesanan'])->name('pemesanan.cetak');
        Route::get('/pembayaran', [LaporanController::class, 'pembayaran'])->name('pembayaran');
        Route::get('/pembayaran/cetak', [LaporanController::class, 'cetakPembayaran'])->name('pembayaran.cetak');
    });

Route::middleware(['auth', 'role:konsumen'])
    ->name('konsumen.')
    ->group(function () {
        Route::get('/pesanan', [KonsumenPemesananController::class, 'index'])->name('pemesanan.index');
        Route::get('/paket-wisata/{paket}/pesan', [KonsumenPemesananController::class, 'create'])->name('pemesanan.create');
        Route::post('/paket-wisata/{paket}/pesan', [KonsumenPemesananController::class, 'store'])->name('pemesanan.store');
        Route::get('/pesanan/{pemesanan}', [KonsumenPemesananController::class, 'show'])->name('pemesanan.show');
        Route::get('/pesanan/{pemesanan}/bayar', [KonsumenPemesananController::class, 'bayar'])->name('pemesanan.bayar');
        Route::post('/pesanan/{pemesanan}/sync-midtrans', [KonsumenPemesananController::class, 'syncMidtrans'])->name('pemesanan.syncMidtrans');
        Route::post('/pesanan/{pemesanan}/bayar', [KonsumenPemesananController::class, 'storePembayaran'])->name('pemesanan.storePembayaran');
    });
