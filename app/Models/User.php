<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_BENDAHARA = 'bendahara';
    public const ROLE_DIREKTUR = 'direktur';
    public const ROLE_KONSUMEN = 'konsumen';

    public const LEVEL_LABELS = [
        self::ROLE_ADMIN => 'Staff Admin',
        self::ROLE_BENDAHARA => 'Bendahara',
        self::ROLE_DIREKTUR => 'Direktur',
        self::ROLE_KONSUMEN => 'Konsumen',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'nama',
        'email',
        'level',
        'aktif',
        'no_hp',
        'alamat',
        'password',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'aktif' => 'boolean',
        ];
    }

    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class);
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function kupon(): HasMany
    {
        return $this->hasMany(Kupon::class);
    }

    public function isAdmin(): bool
    {
        return $this->level === self::ROLE_ADMIN;
    }

    public function isBendahara(): bool
    {
        return $this->level === self::ROLE_BENDAHARA;
    }

    public function isDirektur(): bool
    {
        return $this->level === self::ROLE_DIREKTUR;
    }

    public function isKonsumen(): bool
    {
        return $this->level === self::ROLE_KONSUMEN;
    }

    public function isAktif(): bool
    {
        return (bool) $this->aktif;
    }

    public function labelLevel(): string
    {
        return self::LEVEL_LABELS[$this->level] ?? ucfirst($this->level);
    }

    /**
     * @return array<string, string>
     */
    public static function levelLabels(): array
    {
        return self::LEVEL_LABELS;
    }
}
