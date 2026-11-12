# SISTEM PEMESANAN PAKET WISATA BERBASIS WEB — PT. PUMA STAR JAYA
> **Tugas Ujian Tengah Semester (UTS) — Basis Data Lanjut**  
> Program Studi S1 Ilmu Komputer — Universitas Muhammadiyah Metro  
> Pengembang: **Rangga Febriawan**

---

## 📑 LAPORAN UJIAN TENGAH SEMESTER (UTS)

### **JUDUL TUGAS:**
**IMPLEMENTASI DAN PERANCANGAN SISTEM INFORMASI PEMESANAN PAKET WISATA BERBASIS WEB PADA PT. PUMA STAR JAYA MENGGUNAKAN BASIS DATA RELASIONAL**

---

## BAB I: PENDAHULUAN

### 1.1 Latar Belakang Masalah
PT. Puma Star Jaya merupakan biro perjalanan wisata (*tour & travel*) yang berdiri sejak 10 Agustus 2016 di Kota Metro, Lampung. Perusahaan ini melayani perjalanan wisata nusantara, *study tour*, kunjungan industri, KKL, hingga ziarah Wali Songo dengan armada bus pariwisata.

Dalam sistem operasional sebelumnya, PT. Puma Star Jaya menghadapi sejumlah kendala terkait pengelolaan data:
1. **Pemesanan Manual:** Calon konsumen harus memesan melalui pesan singkat (WhatsApp) atau datang langsung ke kantor, menyebabkan antrean dan risiko salah pencatatan jadwal.
2. **Pengelolaan Data Tercecer:** Data calon pelanggan, paket wisata, dan riwayat pesanan dicatat terpisah menggunakan spreadsheet Microsoft Excel, yang rentan terhadap kehilangan data, redundansi, dan inkonsistensi.
3. **Pencatatan Pembayaran:** Bukti pembayaran tunai maupun transfer diverifikasi secara konvensional tanpa adanya validasi data terpusat.
4. **Keterlambatan Pelaporan:** Pembuatan laporan pemesanan dan laporan keuangan berkala untuk Direktur memerlukan rekapitulasi manual yang memakan waktu dan berisiko salah hitung.

Oleh karena itu, dibangun **Sistem Informasi Pemesanan Paket Wisata Berbasis Website** yang memanfaatkan basis data relasional (RDBMS) terstruktur agar integrasi data dari pemesanan hingga transaksi keuangan berjalan efisien, aman, dan transparan.

### 1.2 Rumusan Masalah
1. Bagaimana merancang skema basis data relasional yang mampu mengelola data master paket wisata, pemesanan, dan transaksi keuangan secara terintegrasi?
2. Bagaimana mengimplementasikan hak akses bertingkat (*Role-Based Access Control*) untuk 4 aktor (Admin, Bendahara, Direktur, dan Konsumen)?
3. Bagaimana mengintegrasikan sistem pemesanan website dengan mekanisme pembayaran online otomatis (Payment Gateway Midtrans)?

### 1.3 Tujuan Proyek
1. Merancang basis data relasional yang ternormalisasi untuk sistem pemesanan tiket wisata.
2. Membangun aplikasi web menggunakan framework Laravel dan MySQL yang mencakup proses CRUD paket, reservasi tiket, validasi pembayaran otomatis, pencetakan kuitansi, dan rekapitulasi laporan.
3. Mengurangi redundansi data dan meningkatkan integritas data transaksi pada PT. Puma Star Jaya.

---

## BAB II: ANALISIS DAN PERANCANGAN BASIS DATA

### 2.1 Entitas dan Atribut
Sistem memiliki 5 entitas data utama:
1. **`users`** : Menyimpan identitas pengguna dan level hak akses (*Admin, Bendahara, Direktur, Konsumen*).
2. **`paket`** : Menyimpan katalog perjalanan wisata (judul, deskripsi, destinasi, fasilitas, jenis bus, harga, foto).
3. **`pemesanan`** : Menyimpan data reservasi tiket (kode pemesanan, relasi pengguna, relasi paket, tanggal keberangkatan, jumlah peserta/pax, harga total, snap token).
4. **`transaksi`** : Menyimpan catatan pembayaran (relasi pemesanan, status *menunggu/valid/ditolak*, metode bayar, bukti transfer, catatan bendahara).
5. **`kupon`** : Menyimpan voucher potongan harga promosi.

### 2.2 Relasi Antar Tabel (Entity Relationship)
* **One-to-Many (`users` &rarr; `pemesanan`)**: Satu konsumen dapat membuat banyak pemesanan tiket wisata.
* **One-to-Many (`paket` &rarr; `pemesanan`)**: Satu paket wisata dapat dipesan berkali-kali dalam berbagai pemesanan.
* **One-to-Many / One-to-One (`pemesanan` &rarr; `transaksi`)**: Satu pemesanan dapat memiliki riwayat transaksi/pembayaran (termasuk jika pembayaran sebelumnya ditolak dan dilakukan pembayaran ulang).
* **One-to-Many (`users` &rarr; `kupon`) & Many-to-One (`pemesanan` &rarr; `kupon`)**: Satu kupon dapat digunakan untuk memotong total biaya pemesanan.

### 2.3 Skema Tabel Database (DDL MySQL)

```sql
-- 1. Tabel Users
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL UNIQUE,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `level` enum('admin','bendahara','direktur','konsumen') NOT NULL DEFAULT 'konsumen',
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- 2. Tabel Paket Wisata
CREATE TABLE `paket` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `destinasi` varchar(255) NOT NULL,
  `fasilitas` varchar(255) DEFAULT NULL,
  `bus` varchar(255) DEFAULT NULL,
  `harga` decimal(12,2) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- 3. Tabel Kupon
CREATE TABLE `kupon` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) NOT NULL UNIQUE,
  `potongan` decimal(12,2) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `digunakan` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

-- 4. Tabel Pemesanan
CREATE TABLE `pemesanan` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_pemesanan` varchar(255) NOT NULL UNIQUE,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `paket_id` bigint(20) UNSIGNED NOT NULL,
  `kupon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal_berangkat` date NOT NULL,
  `jumlah_pax` int(10) UNSIGNED NOT NULL,
  `nama_rombongan` varchar(255) DEFAULT NULL,
  `no_hp` varchar(20) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `snap_token` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`kupon_id`) REFERENCES `kupon` (`id`) ON DELETE SET NULL
);

-- 5. Tabel Transaksi
CREATE TABLE `transaksi` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `pemesanan_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('menunggu','valid','ditolak') NOT NULL DEFAULT 'menunggu',
  `alat_pembayaran` varchar(50) NOT NULL DEFAULT 'transfer',
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `jumlah_bayar` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tanggal_bayar` datetime DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE
);
```

---

## BAB III: IMPLEMENTASI SISTEM DAN FITUR

### 3.1 Arsitektur Sistem
* **Backend:** PHP 8.2+ / PHP 8.5 + Framework Laravel 12 (Arsitektur Model-View-Controller / MVC).
* **Database:** MySQL 8.0 dengan *Foreign Key Constraints* (Integritas referensial `CASCADE` dan `SET NULL`).
* **Frontend:** Blade Templating Engine + Vanilla CSS Modern + JavaScript Responsif.
* **Payment Gateway:** Midtrans Snap SDK (QRIS, GoPay, ShopeePay, Virtual Account BCA/Mandiri/BRI/BNI).

### 3.2 Pembagian Hak Akses Pengguna (RBAC)

1. **Staff Admin (Pengelola Master Data):**
   * Mengelola katalog paket wisata (Create, Read, Update, Delete data paket & foto).
   * Manajemen akun pengguna (mengaktifkan/menonaktifkan akun, reset password).
   * Memantau seluruh pemesanan yang masuk beserta rekap laporan.

2. **Bendahara (Verifikasi & Keuangan):**
   * Memeriksa bukti pembayaran yang masuk.
   * Melakukan validasi pembayaran (*Terima & Validasi* atau *Tolak dengan Catatan*).
   * Mencetak kuitansi pembayaran resmi bertanda tangan dan stempel biro.
   * Menghasilkan rekap Laporan Pembayaran dan Pemesanan per periode.

3. **Direktur (Pimpinan / Evaluasi Eksekutif):**
   * Memantau statistik performa penjualan biro melalui dashboard ringkas.
   * Meninjau dan mencetak Laporan Pemesanan & Pembayaran berdasarkan filter rentang tanggal.

4. **Konsumen (Pelanggan / Pemesan):**
   * Mendaftar akun dan login mandiri (dilengkapi fitur *Quick Role Login* untuk demo).
   * Menjelajahi katalog destinasi wisata dan galeri foto tempat wisata.
   * Melakukan pemesanan tiket online (memilih tanggal berangkat, jumlah rombongan/pax).
   * Melakukan pembayaran instan real-time via Midtrans (tanpa perlu kirim bukti fisik/struk).
   * Memantau status pesanan secara langsung (*Belum Bayar &rarr; Pembayaran Valid / Lunas*).

---

## BAB IV: PENGUJIAN SISTEM (TESTING)

Pengujian dilakukan dengan metode **Black Box Testing** untuk menguji fungsionalitas sistem basis data dan antarmuka:

| No | Modul / Kasus Uji | Skenario Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|:---:|
| 1 | Registrasi Konsumen | Menginputkan data konsumen baru dengan username unik | Akun tersimpan di tabel `users` dengan level `konsumen` | **Berhasil** |
| 2 | Pemesanan Tiket | Memilih paket wisata dan menginputkan 3 pax | Total harga dihitung otomatis dan tersimpan di tabel `pemesanan` | **Berhasil** |
| 3 | Pembayaran Midtrans | Menekan tombol "Bayar Sekarang via Midtrans" | Pop-up pembayaran muncul dan status langsung sinkron otomatis | **Berhasil** |
| 4 | Validasi Transaksi | Menguji transaksi pembayaran lewat simulasi Midtrans | Status transaksi berubah menjadi `valid`, pesanan lunas | **Berhasil** |
| 5 | Cetak Kuitansi & Laporan | Mencetak kuitansi dan filter tanggal laporan | Tampilan cetak PDF/kuitansi muncul lengkap dengan logo dan tanda tangan | **Berhasil** |

---

## BAB V: KESIMPULAN

1. Perancangan basis data relasional pada PT. Puma Star Jaya berhasil menormalisasi data dari format manual (Excel) menjadi 5 entitas terstruktur (`users`, `paket`, `pemesanan`, `transaksi`, `kupon`) dengan integritas referensial yang kuat.
2. Sistem informasi berbasis web ini telah berhasil mengotomasi proses bisnis biro perjalanan: mulai dari pemesanan paket oleh konsumen, pembayaran otomatis via Payment Gateway Midtrans, validasi bendahara, hingga penerbitan kuitansi dan laporan manajerial bagi direktur.
3. Efisiensi pencatatan data meningkat, mengurangi risiko manipulasi atau kehilangan data, dan mempermudah pelaporan keuangan secara akurat dan transparan.

---

## 🚀 Panduan Instalasi Lokal (Setup)

1. **Clone Repository:**
   ```bash
   git clone https://github.com/Ranggafebriawan02/sistempemesanan-tiketwisata.git
   cd sistempemesanan-tiketwisata
   ```
2. **Install Dependensi:**
   ```bash
   composer install
   ```
3. **Konfigurasi Environment (`.env`):**
   Salin `.env.example` ke `.env` lalu sesuaikan konfigurasi database dan Midtrans:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=puma_star_jaya
   DB_USERNAME=root
   DB_PASSWORD=

   MIDTRANS_SERVER_KEY=your-midtrans-server-key-here
   MIDTRANS_CLIENT_KEY=your-midtrans-client-key-here
   MIDTRANS_IS_PRODUCTION=false
   ```
4. **Migrasi Database & Key:**
   ```bash
   php artisan key:generate
   php artisan migrate
   ```
5. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Buka browser di `http://127.0.0.1:8000`.
