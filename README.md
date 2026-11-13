<p align="center">
  <img src="public/docs_images/image1.png" width="180" alt="Logo UM Metro">
</p>

<h1 align="center">LAPORAN UJIAN TENGAH SEMESTER (UTS)<br>MATA KULIAH: BASIS DATA LANJUT</h1>

<p align="center">
  <strong>JUDUL TUGAS:</strong><br>
  <strong>PERANCANGAN SISTEM INFORMASI PEMESANAN PAKET WISATA PADA PT. PUMA STAR JAYA BERBASIS WEBSITE</strong>
</p>

<p align="center">
  <strong>Disusun Oleh:</strong> Rangga Febriawan<br>
  <strong>Mata Kuliah:</strong> Basis Data Lanjut<br>
  <strong>Program Studi:</strong> S1 Ilmu Komputer<br>
  <strong>Dosen Pengampu:</strong> Dedi Irawan S.Kom M.Kom<br><br>
  <strong>PROGRAM STUDI ILMU KOMPUTER</strong><br>
  <strong>FAKULTAS ILMU KOMPUTER</strong><br>
  <strong>UNIVERSITAS MUHAMMADIYAH METRO</strong><br>
  <strong>TAHUN 2026/2027</strong>
</p>

---

## BAB I: PENDAHULUAN

### 1.1 Latar Belakang Masalah
PT. Puma Star Jaya merupakan biro perjalanan wisata (*tour & travel*) yang berdiri sejak 10 Agustus 2016 di Kota Metro, Lampung. Perusahaan ini melayani perjalanan wisata nusantara, *study tour*, kunjungan industri, KKL, hingga ziarah Wali Songo dengan armada bus pariwisata. Dalam sistem operasional sebelumnya, PT. Puma Star Jaya menghadapi sejumlah kendala terkait pengelolaan data:

1. **Pemesanan Manual:** Calon konsumen harus memesan melalui pesan singkat (WhatsApp) atau datang langsung ke kantor, menyebabkan antrean dan risiko salah pencatatan jadwal.
2. **Pengelolaan data tercecer:** Data calon pelanggan, paket wisata, dan riwayat pesanan dicatat terpisah menggunakan spreadsheet Microsoft Excel, yang rentan terhadap kehilangan data, redundansi, dan inkonsistensi.
3. **Pencatatan Pembayaran:** Bukti pembayaran tunai maupun transfer diverifikasi secara konvensional tanpa adanya validasi data terpusat, menyulitkan pembuatan rekap kuitansi.
4. **Keterlambatan Pelaporan:** Pembuatan laporan pemesanan dan laporan keuangan berkala untuk Direktur memerlukan rekapitulasi manual yang memakan waktu dan berisiko salah hitung.

Oleh karena itu, diperlukan perancangan dan implementasi Sistem Informasi Pemesanan Paket Wisata Berbasis Website yang memanfaatkan basis data relasional (RDBMS) terstruktur agar integrasi data dari pemesanan hingga transaksi keuangan berjalan efisien, aman, dan transparan.

### 1.2 Rumusan Masalah
Bagaimana merancang sistem informasi pemesanan paket wisata pada PT. Puma Star Jaya berbasis website?

### 1.3 Tujuan Proyek
Tujuan dilakukan penelitian ini untuk merancang sistem informasi pemesanan paket wisata pada PT. Puma Star Jaya berbasis web.

### 1.4 Pembatasan Masalah
* User yang terlibat adalah staff admin, konsumen, bendahara, dan direktur.
* Sistem informasi dibangun menggunakan Bahasa pemrograman PHP dengan Framework Laravel untuk mengelola database menggunakan MySQL dan phpMyAdmin. XAMPP digunakan untuk web server, teks editor menggunakan Visual Studio Code, dan Google Chrome untuk menampilkan hasil sistem.

---

## BAB II: ANALISIS DAN PERANCANGAN BASIS DATA

### 2.1 Entitas dan Atribut
Sistem memiliki 5 entitas data utama:
* **`users`** : Menyimpan identitas pengguna dan level hak akses (Admin, Bendahara, Direktur, Konsumen).
* **`paket`** : Menyimpan katalog perjalanan wisata (judul, deskripsi, destinasi, fasilitas, jenis bus, harga, foto).
* **`pemesanan`** : Menyimpan data reservasi tiket (kode pemesanan, relasi pengguna, relasi paket, tanggal keberangkatan, jumlah peserta/pax, harga total, snap token).
* **`transaksi`** : Menyimpan catatan pembayaran (relasi pemesanan, status menunggu/valid/ditolak, metode bayar, bukti transfer, catatan bendahara).
* **`kupon`** : Menyimpan voucher potongan harga promosi.

### 2.2 Relasi Antar Tabel (Entity Relationship)
* **One-to-Many (`users` &rarr; `pemesanan`)**: Satu konsumen dapat membuat banyak pemesanan tiket wisata.
* **One-to-Many (`paket` &rarr; `pemesanan`)**: Satu paket wisata dapat dipesan berkali-kali dalam berbagai pemesanan.
* **One-to-Many / One-to-One (`pemesanan` &rarr; `transaksi`)**: Satu pemesanan dapat memiliki riwayat transaksi/pembayaran (termasuk jika pembayaran sebelumnya ditolak dan dilakukan pembayaran ulang).
* **One-to-Many (`users` &rarr; `kupon`) & Many-to-One (`pemesanan` &rarr; `kupon`)**: Satu kupon dapat digunakan untuk memotong total biaya pemesanan.

---

## 📊 DIAGRAM SISTEM (USE CASE, ACTIVITY, FLOWCHART, CLASS DIAGRAM)

### 1. Use Case Diagram
Diagram interaksi antar 4 Aktor (Konsumen, Staff Admin, Bendahara, Direktur) terhadap fungsi sistem:
<p align="center">
  <img src="public/docs_images/diagrams/usecase_diagram.png" alt="Use Case Diagram PT Puma Star Jaya" width="850">
</p>

### 2. Activity Diagram
Diagram aktivitas proses pemesanan tiket paket wisata oleh Konsumen hingga validasi bendahara:
<p align="center">
  <img src="public/docs_images/diagrams/activity_diagram.png" alt="Activity Diagram Pemesanan Wisata" width="500">
</p>

### 3. Flowchart Sistem
Bagan alir logika sistem mulai dari autentikasi login, routing hak akses peran, alur transaksi pembayaran online otomatis Midtrans, hingga cetak kuitansi dan laporan:
<p align="center">
  <img src="public/docs_images/diagrams/flowchart_diagram.png" alt="Flowchart Sistem PT Puma Star Jaya" width="600">
</p>

### 4. Class Diagram
Diagram struktur kelas objek database relasional beserta atribut dan relasi method pada model sistem:
<p align="center">
  <img src="public/docs_images/diagrams/class_diagram.png" alt="Class Diagram PT Puma Star Jaya" width="700">
</p>

---

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
Sistem Informasi Pemesanan Paket Wisata pada PT. Puma Star Jaya dikembangkan menggunakan teknologi berbasis web. Sistem ini dirancang untuk mengintegrasikan proses pengelolaan paket wisata, pemesanan, pembayaran, hingga penyajian laporan dalam satu aplikasi.

Dalam proses pengembangannya, sistem menggunakan arsitektur Model-View-Controller (MVC) dengan framework Laravel. Adapun teknologi yang digunakan meliputi:
* **Backend:** PHP 8.2 + Framework Laravel 12 (Arsitektur Model-View-Controller / MVC).
* **Database:** MySQL 8.0 dengan Foreign Key Constraints (Integritas referensial `CASCADE` dan `SET NULL`).
* **Frontend:** Blade Templating Engine + Vanilla CSS Modern + JavaScript Responsif.
* **Payment Gateway:** Midtrans Snap SDK (QRIS, GoPay, ShopeePay, Virtual Account BCA/Mandiri/BRI/BNI).

### 3.2 Pembagian Hak Akses Pengguna (RBAC)
Sistem menerapkan pembagian hak akses berdasarkan *Role-Based Access Control* (RBAC). Pembagian ini bertujuan agar setiap pengguna dapat mengakses fitur sesuai dengan tugas dan tanggung jawabnya.

Adapun pembagian hak akses dalam sistem adalah sebagai berikut:
* **3.2.1 Admin:** Admin memiliki hak akses untuk mengelola data utama sistem. Fitur yang disediakan meliputi pengelolaan paket wisata, pengelolaan akun pengguna, serta pemantauan data pemesanan yang masuk ke dalam sistem.
* **3.2.2 Bendahara:** Bendahara bertanggung jawab terhadap pengelolaan transaksi pembayaran. Hak akses bendahara meliputi pemeriksaan bukti pembayaran, validasi atau penolakan pembayaran, pencetakan kuitansi, serta pengelolaan laporan pembayaran dan pemesanan.
* **3.2.3 Direktur:** Direktur memiliki hak akses untuk memantau informasi dan laporan yang dihasilkan sistem. Fitur yang tersedia meliputi pemantauan statistik pemesanan, informasi pembayaran, serta pencetakan laporan berdasarkan rentang tanggal tertentu.
* **3.2.4 Konsumen:** Konsumen merupakan pengguna yang melakukan pemesanan paket wisata melalui website. Konsumen dapat melakukan registrasi akun, login, melihat katalog paket wisata, melakukan pemesanan, memilih metode pembayaran, serta memantau status pemesanan yang telah dilakukan.

---

### 3.3 Hasil Implementasi Antarmuka Sistem
Hasil implementasi antarmuka merupakan tampilan dari sistem informasi pemesanan paket wisata berbasis web yang telah dikembangkan berdasarkan hasil analisis dan perancangan sebelumnya. Antarmuka sistem dirancang untuk memudahkan pengguna dalam mengakses berbagai fitur sesuai dengan hak akses masing-masing.

Implementasi antarmuka mencakup halaman utama, halaman autentikasi, pengelolaan paket wisata, proses pemesanan, pembayaran, serta pengelolaan data dan laporan. Berikut merupakan hasil implementasi dari masing-masing halaman pada sistem informasi pemesanan paket wisata PT. Puma Star Jaya.

#### Halaman Konsumen

##### 3.3.1 Halaman Utama
![Halaman Utama](public/docs_images/image2.png)

##### 3.3.2 Halaman Login
![Halaman Login](public/docs_images/image3.png)

##### 3.3.3 Halaman Registrasi
![Halaman Registrasi](public/docs_images/image4.png)

##### 3.3.4 Halaman Katalog Paket Wisata
![Halaman Katalog Paket Wisata](public/docs_images/image5.png)

##### 3.3.5 Halaman Detail Paket Wisata
![Halaman Detail Paket Wisata](public/docs_images/image6.png)

##### 3.3.6 Halaman Form Pemesanan
![Halaman Form Pemesanan](public/docs_images/image7.png)

##### 3.3.7 Halaman Pembayaran
![Halaman Pembayaran](public/docs_images/image8.png)

##### 3.3.8 Halaman Riwayat Pembayaran
![Halaman Riwayat Pembayaran](public/docs_images/image9.png)

---

#### Halaman Admin

##### 3.3.9 Halaman Dashboard Admin
![Halaman Dashboard Admin](public/docs_images/image10.png)

##### 3.3.10 Halaman Data Paket Wisata
![Halaman Data Paket Wisata](public/docs_images/image11.png)

##### 3.3.11 Halaman Tambah/Edit Paket
![Halaman Tambah/Edit Paket](public/docs_images/image12.png)

##### 3.3.12 Halaman Manajemen Pengguna
![Halaman Manajemen Pengguna](public/docs_images/image13.png)

##### 3.3.13 Halaman Data Pemesanan
![Halaman Data Pemesanan](public/docs_images/image14.png)

---

#### Halaman Bendahara

##### 3.3.14 Halaman Dashboard Bendahara
![Halaman Dashboard Bendahara](public/docs_images/image15.png)

##### 3.3.15 Halaman Data Pembayaran
![Halaman Data Pembayaran](public/docs_images/image16.png)

##### 3.3.16 Halaman Detail Verifikasi Pembayaran
![Halaman Detail Verifikasi Pembayaran](public/docs_images/image17.png)

##### 3.3.17 Halaman Cetak Kwitansi
![Halaman Cetak Kwitansi](public/docs_images/image18.png)

---

#### Halaman Direktur

##### 3.3.18 Halaman Dashboard Direktur
![Halaman Dashboard Direktur](public/docs_images/image19.png)

##### 3.3.19 Halaman Laporan Pemesanan
![Halaman Laporan Pemesanan](public/docs_images/image20.png)

##### Halaman Laporan Pembayaran
![Halaman Laporan Pembayaran](public/docs_images/image21.png)

---

#### Implementasi Basis Data
![Relasi Basis Data](public/docs_images/image22.png)

---

## BAB IV: PENGUJIAN SISTEM (TESTING)

### 4.1 Metode Pengujian
Pengujian sistem dilakukan untuk mengetahui apakah seluruh fungsi yang terdapat pada Sistem Informasi Pemesanan Paket Wisata PT. Puma Star Jaya dapat berjalan sesuai dengan kebutuhan yang telah ditentukan. Metode pengujian yang digunakan adalah Black Box Testing.

Black Box Testing merupakan metode pengujian perangkat lunak yang berfokus pada pengujian fungsi sistem berdasarkan masukan (*input*) dan keluaran (*output*) yang dihasilkan, tanpa memeriksa struktur kode program secara langsung.

Pengujian dilakukan dengan mencoba beberapa fungsi utama sistem, mulai dari proses registrasi konsumen, pemesanan paket wisata, pembayaran melalui Midtrans, pengunggahan bukti pembayaran manual, validasi pembayaran oleh bendahara, hingga pencetakan kuitansi dan laporan.

### 4.2 Skenario Pengujian
Skenario pengujian disusun berdasarkan fitur utama yang terdapat pada Sistem Informasi Pemesanan Paket Wisata PT. Puma Star Jaya. Setiap pengujian dilakukan dengan memberikan masukan pada sistem, kemudian mengamati hasil yang dihasilkan untuk mengetahui kesesuaian fungsi dengan kebutuhan yang telah dirancang.

Berikut merupakan tabel skenario pengujian sistem menggunakan metode Black Box Testing.

#### Tabel 4.1 Skenario Pengujian Sistem
![Tabel 4.1 Skenario Pengujian Sistem](public/docs_images/image23.png)

### 4.3 Hasil Pengujian
Berdasarkan skenario pengujian yang telah disusun, dilakukan pengujian terhadap fungsi-fungsi utama sistem. Pengujian bertujuan untuk memastikan bahwa setiap fitur dapat menghasilkan keluaran sesuai dengan proses yang telah dirancang.

Hasil pengujian menunjukkan bahwa fungsi registrasi konsumen, pemesanan paket wisata, pembayaran, validasi transaksi oleh bendahara, serta pencetakan kuitansi dan laporan dapat dijalankan sesuai dengan kebutuhan sistem.

Adapun rekapitulasi hasil pengujian sistem dapat dilihat pada tabel berikut.

#### Tabel 4.2 Hasil Pengujian Sistem
![Tabel 4.2 Hasil Pengujian Sistem](public/docs_images/image24.png)

Berdasarkan hasil pengujian menggunakan metode Black Box Testing, dapat disimpulkan bahwa fungsi-fungsi utama pada Sistem Informasi Pemesanan Paket Wisata PT. Puma Star Jaya telah berjalan sesuai dengan hasil yang diharapkan. Pengujian ini menunjukkan bahwa sistem dapat mendukung proses pemesanan paket wisata, pengelolaan pembayaran, validasi transaksi, serta penyajian laporan.

---

## BAB V: KESIMPULAN

### 5.1 Kesimpulan
Berdasarkan hasil analisis, perancangan, implementasi, dan pengujian, Sistem Informasi Pemesanan Paket Wisata pada PT. Puma Star Jaya berhasil dikembangkan menggunakan Laravel 12 dan MySQL. Sistem ini memiliki fitur pengelolaan paket wisata, pemesanan, pembayaran melalui Midtrans maupun transfer manual, verifikasi pembayaran, pencetakan kuitansi, serta penyajian laporan. Sistem juga menerapkan pembagian hak akses bagi Admin, Bendahara, Direktur, dan Konsumen. Pengujian menggunakan metode Black Box Testing dilakukan untuk memastikan fitur sistem berjalan sesuai dengan kebutuhan.

### 5.2 Saran
Berdasarkan hasil pengembangan sistem, beberapa saran yang dapat diberikan adalah:
1. Menambahkan notifikasi otomatis untuk memberikan informasi kepada konsumen terkait status pemesanan dan pembayaran.
2. Meningkatkan keamanan sistem, terutama dalam pengelolaan data pengguna dan transaksi pembayaran.
3. Mengembangkan fitur laporan agar informasi yang disajikan lebih lengkap dan membantu perusahaan dalam mengambil keputusan.
4. Melakukan pengujian dan pemeliharaan sistem secara berkala agar sistem tetap berjalan dengan baik.
