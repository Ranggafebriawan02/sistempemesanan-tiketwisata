<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pemesanan')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('paket_id')->constrained('paket')->cascadeOnDelete();
            $table->foreignId('kupon_id')->nullable()->constrained('kupon')->nullOnDelete();
            $table->date('tanggal_berangkat');
            $table->unsignedInteger('jumlah_pax');
            $table->string('nama_rombongan')->nullable();
            $table->string('no_hp', 20);
            $table->decimal('harga', 12, 2);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
