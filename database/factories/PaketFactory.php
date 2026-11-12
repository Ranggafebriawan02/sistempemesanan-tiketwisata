<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paket>
 */
class PaketFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => fake()->unique()->sentence(4),
            'deskripsi' => fake()->paragraph(),
            'destinasi' => fake()->city(),
            'fasilitas' => 'Bus AC, penginapan, makan 3x sehari, pemandu wisata',
            'bus' => 'Bus Executive',
            'harga' => fake()->numberBetween(500_000, 5_000_000),
            'foto' => null,
        ];
    }
}
