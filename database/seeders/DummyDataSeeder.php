<?php

namespace Database\Seeders;

use App\Models\Kupon;
use App\Models\Paket;
use App\Models\Pemesanan;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tambah 10 Konsumen Baru (Total User Konsumen jadi > 10)
        $daftarKonsumen = [
            ['nama' => 'Ahmad Fauzi', 'username' => 'ahmad_fauzi', 'email' => 'ahmad.fauzi@gmail.com', 'no_hp' => '081271112233', 'alamat' => 'Metro Pusat, Kota Metro'],
            ['nama' => 'Siti Nurhaliza', 'username' => 'siti_nur', 'email' => 'siti.nur@gmail.com', 'no_hp' => '081382223344', 'alamat' => 'Metro Barat, Kota Metro'],
            ['nama' => 'Budi Santoso', 'username' => 'budi_santoso', 'email' => 'budi.santoso@yahoo.com', 'no_hp' => '081993334455', 'alamat' => 'Batanghari, Lampung Timur'],
            ['nama' => 'Dewi Lestari', 'username' => 'dewi_lestari', 'email' => 'dewi.lestari@gmail.com', 'no_hp' => '085714445566', 'alamat' => 'Trimurjo, Lampung Tengah'],
            ['nama' => 'Rian Hidayat', 'username' => 'rian_hidayat', 'email' => 'rian.hidayat@outlook.com', 'no_hp' => '082155556677', 'alamat' => 'Pekalongan, Lampung Timur'],
            ['nama' => 'Anisa Rahmawati', 'username' => 'anisa_rahma', 'email' => 'anisa.rahma@gmail.com', 'no_hp' => '087866667788', 'alamat' => 'Bandar Lampung'],
            ['nama' => 'Hendra Setiawan', 'username' => 'hendra_s', 'email' => 'hendra.setiawan@gmail.com', 'no_hp' => '089677778899', 'alamat' => 'Pringsewu, Lampung'],
            ['nama' => 'Mega Utami', 'username' => 'mega_utami', 'email' => 'mega.utami@gmail.com', 'no_hp' => '081288889900', 'alamat' => 'Metro Timur, Kota Metro'],
            ['nama' => 'Fajar Pratama', 'username' => 'fajar_pratama', 'email' => 'fajar.pratama@gmail.com', 'no_hp' => '085299990011', 'alamat' => 'Seputih Banyak, Lampung Tengah'],
            ['nama' => 'Fitri Handayani', 'username' => 'fitri_h', 'email' => 'fitri.h@gmail.com', 'no_hp' => '081310102020', 'alamat' => 'Metro Selatan, Kota Metro'],
        ];

        $users = [];
        // Pastikan konsumen default ada
        $users[] = User::where('username', 'konsumen')->first();

        foreach ($daftarKonsumen as $k) {
            $users[] = User::updateOrCreate(
                ['username' => $k['username']],
                [
                    'nama' => $k['nama'],
                    'email' => $k['email'],
                    'password' => 'password',
                    'level' => User::ROLE_KONSUMEN,
                    'no_hp' => $k['no_hp'],
                    'alamat' => $k['alamat'],
                    'aktif' => true,
                ]
            );
        }

        // Ambil list paket yang ada
        $listPaket = Paket::all();
        if ($listPaket->isEmpty()) {
            return;
        }

        // 2. Buat Minimal 22 Data Pemesanan & Transaksi Dummy Realistis
        $statusPilihan = [
            'valid', 'valid', 'valid', 'valid', 'valid',
            'menunggu', 'menunggu',
            'ditolak',
            'belum_bayar'
        ];

        $namaRombonganContoh = [
            'Rombongan Pengajian Al-Ikhlas',
            'Keluarga Besar Bpk. Supardi',
            'SMA Negeri 1 Metro (Kelas XII IPS 2)',
            'Himpunan Mahasiswa Ilmu Komputer UM Metro',
            'Alumni SMPN 3 Angkatan 2018',
            'Pegawai Dinas Kesehatan Metro',
            'Guru & Karyawan MTs Muhammadiyah',
            'Komunitas Gowes Lampung Sehat',
            'Keluarga Besar Bani Hasyim',
            'SMK Muhammadiyah 1 Metro',
            'Karyawan Bank Syariah Mandiri Metro',
            'Keluarga Ibu Hj. Maryam',
            'Paguyuban Warga Rejomulyo Metro',
            'Rombongan Majelis Taklim An-Nur',
            'KKL Mahasiswa Fakultas Teknik',
            'Keluarga Bpk. H. Mulyono',
            'Staff PT. Semen Baturaja Lampung',
            'Ikatan Remaja Masjid Al-Falah',
            'Keluarga Besar Trah Kartowijoyo',
            'Purna Paskibraka Metro 2024',
            'Rombongan Jamaah Safari Subuh',
            'Karyawan Toko Busana Muslim Metro'
        ];

        $bendahara = User::where('level', User::ROLE_BENDAHARA)->first() ?? $users[0];

        for ($i = 0; $i < 22; $i++) {
            $user = $users[$i % count($users)];
            $paket = $listPaket[$i % count($listPaket)];
            $jumlahPax = rand(2, 45); // antara keluarga kecil sampai bus rombongan
            $hargaTotal = (float) $paket->harga * $jumlahPax;
            
            // Tanggal berangkat antara 10 hari yang lalu sampai 60 hari ke depan
            $tglBerangkat = Carbon::now()->addDays(rand(-10, 60))->format('Y-m-d');
            $kodePemesanan = 'PMS-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            $pemesanan = Pemesanan::updateOrCreate(
                ['kode_pemesanan' => $kodePemesanan],
                [
                    'user_id' => $user->id,
                    'paket_id' => $paket->id,
                    'kupon_id' => null,
                    'tanggal_berangkat' => $tglBerangkat,
                    'jumlah_pax' => $jumlahPax,
                    'nama_rombongan' => $namaRombonganContoh[$i] ?? 'Rombongan ' . $user->nama,
                    'no_hp' => $user->no_hp ?? '08123456789',
                    'harga' => $hargaTotal,
                    'catatan' => 'Pemesanan paket rombongan via sistem online Puma Star Jaya.',
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                ]
            );

            $status = $statusPilihan[$i % count($statusPilihan)];

            if ($status !== 'belum_bayar') {
                $alatBayar = ($i % 3 === 0) ? 'midtrans' : 'transfer';
                $tglBayar = Carbon::parse($pemesanan->created_at)->addHours(rand(1, 12));

                $catatanTransaksi = match ($status) {
                    'valid' => 'Pembayaran telah divalidasi oleh Bendahara. Dana mutasi rekening cocok.',
                    'menunggu' => 'Konsumen telah mengunggah bukti bayar. Menunggu pengecekan rekening oleh Bendahara.',
                    'ditolak' => 'Bukti transfer buram dan mutasi tidak ditemukan. Harap hubungi staff admin.',
                    default => null,
                };

                Transaksi::updateOrCreate(
                    ['pemesanan_id' => $pemesanan->id],
                    [
                        'user_id' => $user->id,
                        'status' => $status,
                        'alat_pembayaran' => $alatBayar,
                        'bukti_pembayaran' => ($alatBayar === 'transfer') ? 'bukti/oO7j2JzyuG6ymF7BV7qLZkyJPwnh6JIuY5c4Wf0M.png' : null,
                        'jumlah_bayar' => $hargaTotal,
                        'tanggal_bayar' => $tglBayar,
                        'catatan' => $catatanTransaksi,
                        'created_at' => $tglBayar,
                    ]
                );
            }
        }
    }
}
