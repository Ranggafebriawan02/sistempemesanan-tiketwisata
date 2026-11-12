<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pemesanan_id')->constrained('pemesanan')->cascadeOnDelete();
            $table->enum('status', ['menunggu', 'valid', 'ditolak'])->default('menunggu');
            $table->string('alat_pembayaran', 30)->default('transfer');
            $table->string('bukti_pembayaran')->nullable();
            $table->decimal('jumlah_bayar', 12, 2)->default(0);
            $table->dateTime('tanggal_bayar')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
