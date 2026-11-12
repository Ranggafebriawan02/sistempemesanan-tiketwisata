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

    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class);
    }
}
