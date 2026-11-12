<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_VALID = 'valid';
    public const STATUS_DITOLAK = 'ditolak';

    protected $fillable = [
        'user_id',
        'pemesanan_id',
        'status',
        'alat_pembayaran',
        'bukti_pembayaran',
        'jumlah_bayar',
        'tanggal_bayar',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_bayar' => 'decimal:2',
            'tanggal_bayar' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class);
    }
}
