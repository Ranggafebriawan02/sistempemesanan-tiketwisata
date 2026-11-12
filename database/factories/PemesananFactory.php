<?php

namespace Database\Factories;

use App\Models\Paket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pemesanan>
 */
class PemesananFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_pemesanan' => 'PSJ-'.now()->format('Ymd').'-'.strtoupper(Str::random(4)),
            'user_id' => User::factory(),
            'paket_id' => Paket::factory(),
            'kupon_id' => null,
            'tanggal_berangkat' => now()->addWeek()->toDateString(),
            'jumlah_pax' => fake()->numberBetween(1, 10),
            'nama_rombongan' => fake()->company(),
            'no_hp' => fake()->numerify('08##########'),
            'harga' => fake()->numberBetween(500_000, 10_000_000),
            'catatan' => null,
        ];
    }
}
