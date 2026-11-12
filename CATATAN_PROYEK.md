# Catatan Proyek

## 1. Informasi Umum

| Item | Keterangan |
|------|-----------|
| **Nama Proyek** | Sistem Pemesanan Paket Wisata |
| **Perusahaan** | PT. Puma Star Jaya |
| **Jenis Sistem** | Berbasis Website (Web-Based) |
| **Mata Kuliah** | Basis Data Lanjut |
| **Tanggal Dicatat** | 6 November 2026 |
| **Status** | Modul inti selesai → siap Black Box & Beta Testing |

## 2. Sumber Panduan

Panduan pengerjaan ada di folder **`foto skripsi/`** (18 gambar halaman skripsi).
Ringkasan lengkapnya sudah diekstrak ke:

➡️ **[`PANDUAN_SKRIPSI.md`](./PANDUAN_SKRIPSI.md)**

Inti panduan:
- Skripsi **Fina Septiana (NPM 21430102)** — Universitas Muhammadiyah Metro, 2025.
- Judul: *Perancangan Sistem Informasi Pemesanan Paket Wisata pada PT. Puma Star Jaya Berbasis Website*.
- Teknologi: **PHP + Laravel + MySQL/phpMyAdmin + XAMPP + VS Code**.
- Metode: **SDLC**, OOP, Black Box Testing & Beta Testing.
- Aktor: **Konsumen, Staff Admin, Bendahara, Direktur**.
- Fitur utama: kelola paket wisata, pemesanan online, upload & validasi bukti
  pembayaran, cetak kuitansi, laporan pemesanan & pembayaran.

## 3. Ringkasan Kebutuhan

- **Konsumen** — registrasi, login, lihat paket, pesan paket, upload bukti bayar.
- **Staff Admin** — kelola paket wisata, manajemen user, lihat pemesanan.
- **Bendahara** — validasi pembayaran (terima/tolak), cetak kuitansi, laporan.
- **Direktur** — melihat laporan pemesanan & pembayaran.

## 4. Rencana Kerja

### Tahap 1 — Persiapan
- [x] Baca & rangkum panduan skripsi
- [ ] Konfirmasi detail tabel database (Gambar 23 buram)
- [x] Setup project Laravel + koneksi MySQL
- [x] Siapkan struktur folder & konfigurasi `.env`

### Tahap 2 — Desain
- [x] Finalisasi ERD & migration (versi awal)
- [x] Seeder data awal (user per role, paket contoh)
- [x] Rancangan UI/UX halaman utama & dashboard

### Tahap 3 — Implementasi
- [x] Autentikasi & otorisasi multi-role
- [x] Landing page (home, tentang, paket, galeri, kontak)
- [x] CRUD paket wisata (admin) + upload foto
- [x] Manajemen user (admin)
- [x] Pemesanan + upload bukti bayar (konsumen)
- [x] Validasi pembayaran + kuitansi (bendahara)
- [x] Laporan pemesanan & pembayaran (bendahara, direktur) + cetak/PDF

### Tahap 4 — Pengujian
- [ ] Black Box Testing
- [ ] Beta Testing
- [ ] Perbaikan bug

## 5. Keputusan / Catatan Teknis

- Stack mengikuti skripsi: **Laravel (PHP) + MySQL**.
- Role user disimpan di kolom `level` pada tabel `users` (sesuai rancangan skripsi).
- Skema tabel pada `PANDUAN_SKRIPSI.md` masih perlu verifikasi karena hasil OCR.
- Pengujian otomatis memakai database terpisah `puma_star_jaya_test` (diatur di `phpunit.xml`) agar data asli tidak terganggu.
- Foto paket disimpan di `storage/app/public/paket`, bukti pembayaran di `storage/app/public/bukti`.
- Status pembayaran mengikuti transaksi terbaru: `belum_bayar` → `menunggu` → `valid`/`ditolak`. Jika ditolak, konsumen dapat mengunggah ulang bukti (transaksi baru).
- Total tagihan `pemesanan.harga` = harga paket × jumlah pax − potongan kupon.
- Laporan pemesanan difilter berdasarkan `pemesanan.created_at` (tanggal pesan), sedangkan laporan pembayaran berdasarkan `transaksi.tanggal_bayar`.
- Halaman laporan dapat diakses `bendahara` dan `direktur`; cetak/PDF memakai print browser (A4 landscape) tanpa library tambahan.
- Status pembayaran pada daftar/filter memakai subquery transaksi terbaru (`scopeWhereStatusPembayaran`).
- Kolom `users.aktif` menandai akun aktif/nonaktif. Login akun nonaktif ditolak, dan middleware `EnsureUserIsActive` memutus sesi yang masih berjalan ketika akun dinonaktifkan.
- Reset password oleh admin mengembalikan password ke `password` (default demo). Perubahan password manual tersedia pada form edit dan boleh dikosongkan jika tidak diubah.
- Guard manajemen user: admin tidak dapat menonaktifkan/menghapus/menurunkan level akun sendiri, menghapus akun yang punya pemesanan/pembayaran, atau menghilangkan admin terakhir.

## 6. Log Perubahan

| Tanggal | Perubahan |
|---------|-----------|
| 06-11-2026 | Catatan awal dibuat: rencana sistem pemesanan paket wisata PT. Puma Star Jaya berbasis website. |
| 06-11-2026 | Folder `foto skripsi` dibaca (OCR). Panduan skripsi diekstrak ke `PANDUAN_SKRIPSI.md`. |
| 06-11-2026 | Setup Laravel 12 + MySQL `puma_star_jaya`, migration & seeder, autentikasi multi-role, halaman home/katalog/detail/auth/dashboard. Semua halaman lolos uji HTTP 200 & login/registrasi berfungsi. |
| 06-11-2026 | Modul CRUD Paket Wisata (admin): daftar, tambah, edit, hapus, upload/ganti foto, validasi input, proteksi role. Ditambah 10 feature test (`PaketCrudTest`) memakai DB test terpisah `puma_star_jaya_test` — semuanya lulus. |
| 06-11-2026 | Modul Pemesanan Konsumen: form pesanan (tanggal, pax, rombongan, HP, catatan, kupon), hitung total otomatis, kode pesanan `PSJ-YYYYMMDD-XXXX`, halaman Pesanan Saya, detail pesanan, upload/unggah ulang bukti pembayaran, proteksi kepemilikan data. Ditambah 14 feature test (`PemesananKonsumenTest`) + factory Paket/Pemesanan. Total 26 test lulus (105 assertion). Alur live end-to-end juga berhasil. |
| 06-11-2026 | Modul Validasi Pembayaran Bendahara: daftar pemesanan + filter status (semua/menunggu/valid/ditolak/belum bayar) + pencarian, detail pemesanan & pratinjau bukti, terima/tolak dengan catatan wajib saat menolak, serta kuitansi cetak (print/PDF) dengan nominal terbilang. CSS panel diekstrak ke `layouts/panel-style` agar dipakai bersama admin & bendahara. Ditambah 12 feature test (`PembayaranBendaharaTest`); total 38 test lulus (149 assertion). Alur live konsumen → bendahara → kuitansi berhasil. |
| 06-11-2026 | Modul Laporan Pemesanan & Pembayaran untuk bendahara dan direktur: filter periode/status/paket, ringkasan total (pesanan, peserta, nilai, lunas / valid, menunggu, ditolak), tabel rekap, serta halaman cetak A4 landscape siap print/PDF lengkap dengan kop perusahaan dan kolom tanda tangan. Ditambah layout panel direktur dan tautan laporan di sidebar/dashboard. Pengujian: 10 feature test baru (`LaporanTest`); total **48 test lulus (198 assertion)**. Smoke test live untuk 4 halaman laporan + proteksi akses berhasil. |
| 06-11-2026 | Modul Manajemen User (admin): daftar akun dengan filter level, pencarian, dan hitungan pesanan; tambah/edit akun dengan level & status aktif; reset password; aktif/nonaktifkan akun; hapus akun tak terpakai. Ditambah kolom `users.aktif`, penolakan login akun nonaktif, middleware pemutus sesi, serta guard akun sendiri/admin terakhir/data pemesanan. Pengujian: 15 feature test baru (`ManajemenUserTest`); total **63 test lulus (262 assertion)**. Smoke test live buat/edit/nonaktifkan/reset/hapus akun berhasil dan data uji dibersihkan. |
| 06-11-2026 | Perbaikan kompatibilitas PHP 8.5: konstanta `PDO::MYSQL_ATTR_SSL_CA` diganti otomatis ke `Pdo\Mysql::ATTR_SSL_CA` bila tersedia, sehingga peringatan *deprecated* di `config/database.php` tidak muncul lagi. |
| 06-11-2026 | Perapian UI dashboard & navbar: label level memakai `labelLevel()` (Staff Admin/Bendahara/Direktur/Konsumen), kartu statistik dan teks akses cepat/info akun tidak terpotong, serta navbar responsif dengan tautan Dashboard di menu mobile, jarak rapat di layar kecil, dan menu yang menutup otomatis setelah diklik. Verifikasi live 4 role: dashboard HTTP 200 tanpa peringatan. |
