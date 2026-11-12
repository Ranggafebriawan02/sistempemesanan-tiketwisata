<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kupon extends Model
{
    use HasFactory;

    protected $table = 'kupon';

    protected $fillable = [
        'user_id',
        'kode',
        'potongan',
        'digunakan',
    ];

    protected function casts(): array
    {
        return [
            'potongan' => 'decimal:2',
            'digunakan' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class);
    }
}
