<?php

namespace Database\Seeders;

use App\Models\Paket;
use Illuminate\Database\Seeder;

class PaketSeeder extends Seeder
{
    public function run(): void
    {
        $paket = [
            [
                'judul' => 'Ziarah Wali Songo 7 Hari',
                'destinasi' => 'Jawa Timur & Jawa Tengah',
                'deskripsi' => 'Perjalanan ziarah ke makam Wali Songo dengan pemandu wisata berpengalaman. Rute meliputi Sunan Ampel, Sunan Bonang, Sunan Kalijaga, Sunan Kudus, dan Sunan Muria.',
                'fasilitas' => 'Bus AC, penginapan, makan 3x sehari, pemandu wisata, tiket objek wisata',
                'bus' => 'Bus Big Class',
                'harga' => 3500000,
                'foto' => null,
            ],
            [
                'judul' => 'Study Tour Bali 4 Hari 3 Malam',
                'destinasi' => 'Bali',
                'deskripsi' => 'Paket study tour edukatif ke Bali untuk pelajar dan mahasiswa, mengunjungi tempat budaya dan objek wisata populer.',
                'fasilitas' => 'Bus AC, penginapan, makan 3x sehari, pemandu wisata, tiket masuk objek wisata',
                'bus' => 'Bus Executive',
                'harga' => 2750000,
                'foto' => null,
            ],
            [
                'judul' => 'Kuliah Kerja Lapangan Yogyakarta 3 Hari 2 Malam',
                'destinasi' => 'Yogyakarta',
                'deskripsi' => 'Paket KKL ke Yogyakarta dengan kunjungan ke instansi, museum, dan situs budaya.',
                'fasilitas' => 'Bus AC, penginapan, makan 3x sehari, pemandu wisata',
                'bus' => 'Bus Medium',
                'harga' => 1850000,
                'foto' => null,
            ],
            [
                'judul' => 'Wisata Danau Toba 5 Hari 4 Malam',
                'destinasi' => 'Sumatera Utara',
                'deskripsi' => 'Menikmati keindahan Danau Toba, Pulau Samosir, dan budaya Batak dengan itinerary lengkap.',
                'fasilitas' => 'Bus AC, penginapan, makan 3x sehari, pemandu wisata, tiket kapal',
                'bus' => 'Bus Executive',
                'harga' => 4200000,
                'foto' => null,
            ],
            [
                'judul' => 'Kunjungan Industri Bandung 2 Hari 1 Malam',
                'destinasi' => 'Bandung',
                'deskripsi' => 'Kunjungan industri ke perusahaan dan sentra kreatif di Bandung, cocok untuk sekolah dan kampus.',
                'fasilitas' => 'Bus AC, penginapan, makan 2x sehari, pemandu wisata',
                'bus' => 'Bus Medium',
                'harga' => 1250000,
                'foto' => null,
            ],
            [
                'judul' => 'Study Banding Jakarta 3 Hari 2 Malam',
                'destinasi' => 'Jakarta',
                'deskripsi' => 'Study banding ke instansi pemerintahan dan perusahaan di Jakarta, dilengkapi kunjungan wisata kota.',
                'fasilitas' => 'Bus AC, penginapan, makan 3x sehari, pemandu wisata',
                'bus' => 'Bus Executive',
                'harga' => 2100000,
                'foto' => null,
            ],
        ];

        foreach ($paket as $item) {
            Paket::updateOrCreate(['judul' => $item['judul']], $item);
        }
    }
}
