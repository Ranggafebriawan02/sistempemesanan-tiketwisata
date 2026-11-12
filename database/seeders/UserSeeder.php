<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'nama' => 'Admin Puma Star Jaya',
                'email' => 'admin@pumastarjaya.test',
                'level' => User::ROLE_ADMIN,
                'no_hp' => '081234567801',
                'alamat' => 'Jl. Sultan Dumas No. A8-A9, Metro Selatan, Kota Metro',
            ],
            [
                'username' => 'bendahara',
                'nama' => 'Bendahara Puma Star Jaya',
                'email' => 'bendahara@pumastarjaya.test',
                'level' => User::ROLE_BENDAHARA,
                'no_hp' => '081234567802',
                'alamat' => 'Jl. Sultan Dumas No. A8-A9, Metro Selatan, Kota Metro',
            ],
            [
                'username' => 'direktur',
                'nama' => 'Direktur Puma Star Jaya',
                'email' => 'direktur@pumastarjaya.test',
                'level' => User::ROLE_DIREKTUR,
                'no_hp' => '081234567803',
                'alamat' => 'Jl. Sultan Dumas No. A8-A9, Metro Selatan, Kota Metro',
            ],
            [
                'username' => 'konsumen',
                'nama' => 'Konsumen Contoh',
                'email' => 'konsumen@example.test',
                'level' => User::ROLE_KONSUMEN,
                'no_hp' => '081234567804',
                'alamat' => 'Kota Metro, Lampung',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['username' => $user['username']],
                $user + ['password' => 'password', 'aktif' => true]
            );
        }
    }
}
