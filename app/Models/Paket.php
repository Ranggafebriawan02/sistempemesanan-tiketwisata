<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'paket';

    protected $fillable = [
        'judul',
        'deskripsi',
        'destinasi',
        'fasilitas',
        'bus',
        'harga',
        'foto',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
        ];
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) {
            return null;
        }
        $cleanFoto = ltrim($this->foto, '/');
        $basename = basename($cleanFoto);

        // 1. Cek di public/images/galeri
        if (file_exists(public_path('images/galeri/' . $basename))) {
            return asset('images/galeri/' . $basename);
        }

        // 2. Cek di public/storage/
        if (file_exists(public_path('storage/' . $cleanFoto))) {
            return asset('storage/' . $cleanFoto);
        }

        // 3. Fallback direct asset
        return asset('images/galeri/' . $basename);
    }

    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class);
    }
}
