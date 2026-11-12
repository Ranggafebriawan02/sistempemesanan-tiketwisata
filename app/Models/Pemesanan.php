<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pemesanan extends Model
{
    use HasFactory;

    public const STATUS_BELUM_BAYAR = 'belum_bayar';

    protected $table = 'pemesanan';

    protected $fillable = [
        'kode_pemesanan',
        'user_id',
        'paket_id',
        'kupon_id',
        'tanggal_berangkat',
        'jumlah_pax',
        'nama_rombongan',
        'no_hp',
        'harga',
        'snap_token',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_berangkat' => 'date',
            'jumlah_pax' => 'integer',
            'harga' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paket(): BelongsTo
    {
        return $this->belongsTo(Paket::class);
    }

    public function kupon(): BelongsTo
    {
        return $this->belongsTo(Kupon::class);
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    /**
     * Transaksi terbaru untuk pemesanan ini.
     */
    public function transaksiTerakhir()
    {
        return $this->hasOne(Transaksi::class)->latestOfMany();
    }

    /**
     * Status pembayaran mengikuti transaksi terbaru.
     */
    public function statusPembayaran(): string
    {
        return $this->transaksiTerakhir?->status ?? self::STATUS_BELUM_BAYAR;
    }

    public function status(): string
    {
        return $this->statusPembayaran();
    }

    public function labelStatus(): string
    {
        return match ($this->statusPembayaran()) {
            Transaksi::STATUS_VALID => 'Pembayaran Valid',
            Transaksi::STATUS_DITOLAK => 'Pembayaran Ditolak',
            Transaksi::STATUS_MENUNGGU => 'Menunggu Validasi',
            default => 'Belum Bayar',
        };
    }

    /**
     * Konsumen masih bisa mengunggah bukti jika belum bayar atau pembayaran ditolak.
     */
    public function bisaBayar(): bool
    {
        return in_array($this->statusPembayaran(), [
            self::STATUS_BELUM_BAYAR,
            Transaksi::STATUS_DITOLAK,
        ], true);
    }

    /**
     * Filter berdasarkan status pembayaran (transaksi terbaru).
     */
    public function scopeWhereStatusPembayaran(Builder $query, string $status): Builder
    {
        if ($status === self::STATUS_BELUM_BAYAR) {
            return $query->whereDoesntHave('transaksi');
        }

        return $query->whereRaw(
            '(SELECT t.status FROM transaksi t WHERE t.pemesanan_id = pemesanan.id ORDER BY t.id DESC LIMIT 1) = ?',
            [$status]
        );
    }
}
