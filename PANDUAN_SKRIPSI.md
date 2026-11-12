# Panduan Skripsi — Spesifikasi Sistem

> Dokumen ini adalah hasil pembacaan folder `foto skripsi` (18 foto halaman skripsi).
> Foto dibaca melalui OCR (Windows OCR, bahasa en-US), jadi sebagian teks — terutama
> tabel database — perlu diverifikasi ulang. Bagian yang perlu dicek ditandai ⚠️.

---

## 1. Identitas Penelitian (Sumber Panduan)

| Item | Keterangan |
|------|-----------|
| **Judul** | Perancangan Sistem Informasi Pemesanan Paket Wisata pada PT. Puma Star Jaya Berbasis Website |
| **Penulis** | Fina Septiana |
| **NPM** | 21430102 |
| **Program Studi** | Ilmu Komputer |
| **Fakultas** | Ilmu Komputer |
| **Universitas** | Universitas Muhammadiyah Metro |
| **Tahun** | 2025 |
| **Pembimbing** | (1) Sudarmaji, S.Kom., M.M., M.Kom. — (2) Ika Arthalia Wulandari, M.Kom. ⚠️ |
| **Metode** | SDLC (System Development Life Cycle) |

---

## 2. Profil Perusahaan

| Item | Keterangan |
|------|-----------|
| **Nama** | PT. Puma Star Jaya |
| **Bidang** | Biro jasa pelayanan wisata / tour & travel |
| **Berdiri** | 10 Agustus 2016 |
| **Pimpinan** | Bapak Didik Darmadi |
| **Alamat** | Jalan Sultan Dumas No. A8–A9, Metro Selatan, Kota Metro, Lampung |
| **Jumlah Karyawan** | 24 orang |
| **Kontak internal (narasumber)** | Ibu Nurul Huda (Staff Admin) |

**Layanan yang ditawarkan:** paket wisata seluruh nusantara, kunjungan kerja,
kunjungan industri, kuliah kerja lapangan (KKL), study tour, study banding,
ziarah Wali Songo.

**Fasilitas paket:** transportasi (bus), objek wisata, makan, penginapan,
pemandu wisata.

---

## 3. Latar Belakang & Masalah

Kondisi sistem berjalan saat ini:

1. **Pemesanan** dilakukan dengan menghubungi staff admin via **WhatsApp**, lalu
   konsumen datang langsung ke kantor → pelayanan lambat, rawan salah informasi,
   keterlambatan respon, dan antrian panjang.
2. **Pendataan calon konsumen** masih manual memakai **Microsoft Excel** → data
   bisa hilang/salah dan memakan waktu.
3. **Pembayaran** masih manual: **tunai** (datang ke kantor, bayar ke bendahara)
   atau **transfer bank** (kirim bukti transfer ke bendahara), lalu dicatat
   manual di Excel.
4. **Pembuatan laporan** pemesanan & pembayaran masih manual → lambat dan rawan
   kesalahan.

---

## 4. Tujuan & Manfaat

**Tujuan:** merancang sistem informasi pemesanan paket wisata berbasis website
pada PT. Puma Star Jaya untuk mempermudah pemesanan, konfirmasi pembayaran,
mencetak kuitansi, serta mempercepat pembuatan laporan pemesanan dan pembayaran.

**Manfaat:**

| Pihak | Manfaat |
|-------|---------|
| PT. Puma Star Jaya | Menyampaikan informasi paket wisata lebih detail & lengkap |
| Admin | Mudah mengelola data calon konsumen dan data paket wisata |
| Bendahara | Mudah mengelola data pembayaran, lebih efektif |
| Direktur | Mudah memantau laporan pemesanan & laporan keuangan |
| Konsumen | Bisa memesan paket wisata online tanpa datang ke kantor |

---

## 5. Aktor & Hak Akses

Sistem digunakan oleh **4 aktor**:

### a. Konsumen (calon konsumen)
- Registrasi akun & login
- Melihat daftar & detail paket wisata
- Melakukan pemesanan paket wisata
- Upload bukti pembayaran
- Menerima konfirmasi pemesanan

### b. Staff Admin
- Login
- Mengelola data paket wisata (tambah / lihat / ubah / hapus) → output: paket wisata baru
- Manajemen user
- Melihat data pemesanan paket wisata
- Logout

### c. Bendahara
- Login
- Manajemen pemesanan (melihat data pemesanan)
- Melihat detail pemesanan & pembayaran
- **Validasi pembayaran** → tombol **Terima** / **Tolak**
- Mencetak **kuitansi**
- Membuat & mencetak **laporan pemesanan** dan **laporan pembayaran**
- Logout

### d. Direktur
- Login
- Melihat laporan pemesanan dan pembayaran
- Logout

---

## 6. Alur Sistem (Activity Diagram)

### 6.1 Registrasi Akun Konsumen
1. User membuka website → sistem menampilkan halaman utama.
2. User memilih menu **Registrasi** → sistem menampilkan form registrasi.
3. User mengisi data pribadi → klik **Daftar** → sistem menyimpan data.
4. Sistem memvalidasi; jika belum valid user diminta melengkapi.
5. Jika valid → akun dibuat, user menerima username & password.

### 6.2 Login Konsumen
1. User membuka sistem → halaman utama.
2. Pilih **Login** → form login.
3. Input username & password.
4. Sistem verifikasi; jika salah → kembali ke halaman login.
5. Jika benar → masuk ke halaman **home**.

### 6.3 Pemesanan Paket Wisata & Pembayaran
1. User login → halaman home.
2. Pilih menu **Paket Wisata** → daftar paket wisata.
3. Pilih paket → halaman detail paket.
4. Isi data pemesanan → sistem verifikasi; jika tidak sesuai, isi ulang.
5. Jika sesuai → diarahkan ke **upload bukti pembayaran**.
6. User memilih file → klik **Submit** → sistem menyimpan data.

### 6.4 Validasi Pembayaran (Bendahara)
1. Login → verifikasi → dashboard bendahara.
2. Pilih menu **Manajemen Pemesanan** → daftar data pemesanan.
3. Pilih data → klik **Detail** → tampil detail pemesanan & pembayaran.
4. Validasi:
   - **Tidak valid** → pembayaran **ditolak**.
   - **Valid** → pembayaran **diproses**.

### 6.5 Input Paket Wisata (Staff Admin)
1. Login → dashboard staff admin.
2. Sistem menampilkan data paket wisata.
3. Pilih **Tambah Paket Wisata** → form tambah paket.
4. Isi data → simpan → sistem menyimpan → data paket berhasil ditambahkan.

---

## 7. Rancangan Database

### 7.1 Class Diagram (sumber: Gambar 22)

| Class | Atribut |
|-------|---------|
| **user** | id, username, nama, email, password, level, ... |
| **paket** | judul, deskripsi, destinasi, fasilitas, bus, harga, foto |
| **pemesanan** | user_id, paket_id, harga, tanggal_berangkat, nama_rombongan, jumlah_pax, no_hp |
| **transaksi** | user_id, pemesanan_id, status, alat_pembayaran |
| **kupon** | user_id, kode_potongan |

### 7.2 Tabel Relasi (sumber: Gambar 23) ⚠️ *perlu verifikasi ulang*

> OCR pada gambar ini paling buram. Struktur di bawah adalah rekonstruksi terbaik.

**users**
| Kolom | Tipe |
|-------|------|
| id | bigint(20) unsigned |
| username | varchar(255) |
| nama | varchar(255) |
| email | varchar(255) |
| password | varchar(255) |
| level | varchar / int ⚠️ |
| email_verified_at | timestamp |
| remember_token | varchar(100) |
| created_at | timestamp |
| updated_at | timestamp |

**paket**
| Kolom | Tipe |
|-------|------|
| id | bigint(20) unsigned |
| judul | varchar(255) ⚠️ |
| deskripsi | text |
| destinasi | varchar(255) ⚠️ |
| fasilitas | varchar(255) |
| bus | varchar(255) |
| harga | int(11) (di tabel relasi tertulis varchar(255) ⚠️) |
| foto | varchar(255) |
| created_at | timestamp |
| updated_at | timestamp |

**pemesanan**
| Kolom | Tipe |
|-------|------|
| id | bigint(20) unsigned |
| user_id | bigint(20) unsigned |
| paket_id | bigint(20) unsigned |
| tanggal_berangkat | date ⚠️ (di tabel relasi tertulis `date`) |
| jumlah_pax | int(11) |
| no_hp | varchar(255) |
| harga | int(11) ⚠️ (ada di class diagram) |
| nama_rombongan | varchar(255) ⚠️ (ada di class diagram) |
| created_at | timestamp |
| updated_at | timestamp |

**transaksi**
| Kolom | Tipe |
|-------|------|
| id | bigint(20) unsigned |
| user_id | bigint(20) unsigned |
| pemesanan_id | bigint(20) unsigned ⚠️ |
| status | varchar(255) |
| alat_pembayaran | varchar(255) |
| created_at | timestamp |
| updated_at | timestamp |

**kupon**
| Kolom | Tipe |
|-------|------|
| id | bigint(20) unsigned |
| user_id | bigint(20) unsigned |
| kode | varchar(255) |
| potongan | int(11) ⚠️ |
| created_at | timestamp |
| updated_at | timestamp |

### 7.3 Relasi (asumsi dari class diagram)
- `users` 1 — N `pemesanan`
- `paket` 1 — N `pemesanan`
- `pemesanan` 1 — 1/N `transaksi`
- `users` 1 — N `kupon`
- `pemesanan` N — 1 `kupon` (pemakaian kode potongan) ⚠️

---

## 8. Teknologi yang Digunakan (sesuai skripsi)

| Komponen | Teknologi |
|----------|-----------|
| Bahasa pemrograman | **PHP** |
| Framework | **Laravel** |
| Database | **MySQL** + **phpMyAdmin** |
| Web server | **XAMPP** |
| Text editor / IDE | **Visual Studio Code** |
| Browser | **Google Chrome** |
| Pendekatan | **OOP** (Object Oriented Programming) |
| Pengujian | **Black Box Testing** & **Beta Testing** |

---

## 9. Metode Pengembangan — SDLC (5 Tahap)

1. **Analysis** — mengumpulkan data kebutuhan pengguna & persyaratan proyek.
2. **Design** — merancang flowchart, use case, activity diagram, class diagram,
   antarmuka, dan basis data.
3. **Implementation** — mewujudkan rancangan menjadi program/web yang bisa dijalankan.
4. **Testing** — uji unit, integrasi, dan sistem (Black Box & Beta Testing).
5. **Maintenance** — perbaikan bug, pembaruan fitur, penyesuaian kebutuhan.

---

## 10. Rancangan Antarmuka (dari foto)

**Halaman Utama (publik)** — menu:
`Home` | `Tentang Kami` | `Paket Wisata` | `Galeri` | `Contact` | `Register` | `Login`

Halaman lain yang tampak dirancang: dashboard admin, dashboard bendahara,
dashboard direktur, form paket wisata, detail pemesanan, upload bukti pembayaran,
halaman laporan, dan kuitansi.

---

## 11. Ringkasan Fitur Wajib (MVP)

- [x] Registrasi & login (multi-role: konsumen, admin, bendahara, direktur)
- [x] Landing page: home, tentang kami, paket wisata, galeri, kontak
- [x] CRUD paket wisata (admin)
- [x] Manajemen user (admin)
- [x] Pemesanan paket wisata (konsumen)
- [x] Upload bukti pembayaran (konsumen)
- [x] Validasi pembayaran terima/tolak (bendahara)
- [x] Cetak kuitansi (bendahara)
- [x] Laporan pemesanan (bendahara & direktur)
- [x] Laporan pembayaran / keuangan (bendahara & direktur)
- [x] Cetak / export laporan
- [x] Kupon / kode potongan (opsional, ada di rancangan DB)

---

## 12. Catatan Keterbatasan Pembacaan

- Model AI yang dipakai **tidak mendukung input gambar langsung**; teks diekstrak
  dengan OCR bawaan Windows (engine `en-US`), lalu dirapikan manual.
- Nama, angka, dan istilah tertentu bisa salah eja (contoh: `Suttan` → `Sultan`,
  `IO Agustus` → `10 Agustus`).
- **Gambar 23 (Tabel Relasi)** paling sulit dibaca. Perlu dicek ulang dari skripsi
  asli atau foto yang lebih tajam sebelum dipakai sebagai migration final.
