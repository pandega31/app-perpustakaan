# Skenario Demo - Perpustakaan Digital Kampus

Skenario demo aplikasi `app-perpustakaan` untuk UAS. Urutan langkah mengikuti alur kerja petugas perpustakaan, dari login sampai pelaporan, ditutup dengan demo REST API di Postman.

**Perkiraan durasi:** 8-10 menit

---

## A. Persiapan Sebelum Demo

Lakukan semua langkah ini **sebelum** demo dimulai.

1. Pastikan branch aktif adalah `main`:
   ```
   git checkout main
   ```
2. Reset database ke kondisi bersih dengan data dummy:
   ```
   php artisan migrate:fresh --seed
   ```
3. Bersihkan cache konfigurasi:
   ```
   php artisan config:clear
   ```
4. Nyalakan **dua server** di dua terminal terpisah (keduanya harus tetap terbuka):
   ```
   php artisan serve --port=8000
   php artisan serve --port=8011
   ```
   - Port `8000` melayani browser.
   - Port `8011` khusus menerima panggilan API internal dari Dashboard dan Laporan.
5. Siapkan Postman dengan collection `app-perpustakaan` (base URL `http://127.0.0.1:8000/api`, header `Accept: application/json`).
6. Buka browser di `http://127.0.0.1:8000/login`.

**Akun demo (dari `UserSeeder`):**

| Peran | Email | Password |
|-------|-------|----------|
| Admin | `admin@pens.ac.id` | `password` |
| Petugas | `petugas1@pens.ac.id` | `password` |
| Petugas | `petugas2@pens.ac.id` | `password` |

**Data awal hasil seeding:** 5 kategori, 20 buku, 15 anggota, 10 transaksi peminjaman.

---

## B. Alur Demo

### Langkah 1 - Login

1. Buka `/login`.
2. Masukkan `admin@pens.ac.id` dan `password`, lalu klik Login.

**Yang ditunjukkan:**
- Setelah login berhasil, pengguna langsung diarahkan ke **Dashboard** (bukan daftar buku).
- Navbar menampilkan nama petugas **Bambang Sutrisno (Admin)**.
- Menu **Kategori** muncul karena yang login adalah admin.

### Langkah 2 - Tampilkan Dashboard

1. Perhatikan halaman Dashboard (`/`).

**Yang ditunjukkan:**
- Tiga kartu statistik: **Total Buku (20)**, **Total Anggota (15)**, dan **Peminjaman Aktif**.
- Angka ini **tidak diambil langsung dari database**, tetapi dari `GET /api/stats` lewat Laravel HTTP Client. Inilah alasan dua server dibutuhkan.

### Langkah 3 - Tambah Kategori

1. Klik menu **Kategori** → **Tambah Kategori**.
2. Isi contoh data:
   - Nama kategori: `Ekonomi`
   - Deskripsi: `Buku seputar ilmu ekonomi dan bisnis.`
3. Simpan.

**Yang ditunjukkan:**
- Kategori baru muncul di daftar, disertai pesan sukses.
- Fitur ini hanya tersedia untuk admin (dilindungi middleware `admin`).

### Langkah 4 - Tambah Buku

1. Klik menu **Buku** → **Tambah Buku**.
2. Isi contoh data:
   - Judul: `Pengantar Ekonomi Mikro`
   - Penulis: `Hendra Gunawan`
   - Penerbit: `Erlangga`
   - Tahun terbit: `2023`
   - ISBN: `9786020000099`
   - Stok: `5`
   - Kategori: `Ekonomi`
3. Simpan.

**Yang ditunjukkan:**
- Buku baru muncul di daftar `/books`.
- Coba kirim form kosong sekali untuk menunjukkan **validasi** (pesan error per field).
- Buka halaman 2 untuk menunjukkan **pagination** yang tampil rapi.

### Langkah 5 - Tambah Anggota

1. Klik menu **Anggota** → **Tambah Anggota**.
2. Isi contoh data:
   - Nama: `Rizky Ramadhan`
   - NIM: `3125600099`
   - Email: `rizky.ramadhan@pens.ac.id`
   - Nomor telepon: `081234500099`
   - Alamat: `Jl. Keputih No. 10, Surabaya`
   - Status: `aktif`
3. Simpan.

**Yang ditunjukkan:**
- Anggota baru muncul di daftar `/members`.

### Langkah 6 - Buat Peminjaman

1. Klik menu **Peminjaman** → **Tambah Peminjaman**.
2. Pilih anggota `Rizky Ramadhan`.
3. Pilih **dua buku** (termasuk `Pengantar Ekonomi Mikro`).
4. Isi tanggal pinjam (hari ini) dan tanggal kembali (7 hari setelahnya).
5. Simpan.

**Yang ditunjukkan:**
- Satu transaksi bisa mencakup **banyak buku**.
- Petugas pencatat terisi otomatis dari akun yang sedang login.
- Status transaksi baru: **dipinjam**.

### Langkah 7 - Lihat Daftar Peminjaman Aktif

1. Buka daftar **Peminjaman** (`/loans`).
2. Cari transaksi yang baru dibuat.

**Yang ditunjukkan:**
- Transaksi baru tampil dengan status **dipinjam**.
- Kembali ke Dashboard: angka **Peminjaman Aktif bertambah 1**.

### Langkah 8 - Proses Pengembalian

1. Pada transaksi `Rizky Ramadhan`, klik tombol **Kembalikan**.

**Yang ditunjukkan:**
- Status berubah menjadi **dikembalikan** dan tanggal dikembalikan terisi.
- Kembali ke Dashboard: angka **Peminjaman Aktif berkurang 1**.

> Jika tombol pengembalian di sisi web belum diimplementasikan, lakukan langkah ini lewat API: `PUT /api/loans/{id}/kembalikan` di Postman (lihat Langkah 10).

### Langkah 9 - Tampilkan Laporan

1. Klik menu **Laporan** (`/loans/report`).

**Yang ditunjukkan:**
- Tabel transaksi lengkap: anggota, petugas, daftar buku, tanggal, dan status.
- Data diambil dari `GET /api/loans`, bukan query database langsung.
- Navigasi halaman dibangun dari `meta` hasil API.
- Menu **Laporan** aktif, sedangkan menu **Peminjaman** tidak.

### Langkah 10 - Demo REST API di Postman

Tunjukkan beberapa request berikut (semua memakai header `Accept: application/json`):

| No | Request | Yang ditunjukkan |
|----|---------|------------------|
| 1 | `GET /api/books` | Response `data`, `links`, `meta`, kategori ter-nested |
| 2 | `GET /api/books?judul=laskar` | Filter pencarian judul |
| 3 | `POST /api/books` (body lengkap) | Status **201 Created** |
| 4 | `POST /api/books` (body kosong) | Status **422** dengan pesan validasi per field |
| 5 | `GET /api/books/999` | Status **404 Not Found** |
| 6 | `GET /api/loans` | Anggota, petugas, dan daftar buku dalam satu response |
| 7 | `PUT /api/loans/{id}/kembalikan` | Status berubah menjadi `dikembalikan` |
| 8 | `GET /api/stats` | Tiga angka statistik yang dipakai Dashboard |

Dokumentasi lengkap seluruh 11 endpoint ada di [`API.md`](API.md).

### Langkah 11 (Bonus) - Ketahanan saat Server API Mati

1. Matikan terminal server port `8011` (`Ctrl+C`), biarkan port `8000` tetap jalan.
2. Refresh Dashboard dan Laporan.

**Yang ditunjukkan:**
- Dashboard menampilkan angka **0** dan Laporan menampilkan tabel kosong, **tanpa error 500**.
- Ini membuktikan `try/catch ConnectionException` bekerja.
- Nyalakan kembali port `8011` agar data normal lagi.

---

## C. Jika Terjadi Masalah Saat Demo

| Gejala | Penyebab | Solusi |
|--------|----------|--------|
| Dashboard menampilkan angka 0 padahal data ada | Server port `8011` belum menyala, atau `INTERNAL_API_URL` salah | Nyalakan server kedua, cek `.env`, lalu `php artisan config:clear` |
| Halaman Dashboard menggantung | Panggilan API menuju port yang sama dengan server utama | Pastikan `INTERNAL_API_URL` memakai port berbeda (`8011`) |
| Pagination tampil dengan ikon besar | View pagination custom belum terdaftar | Cek `Paginator::defaultView()` di `AppServiceProvider` |
| Data kacau akibat uji coba | Data berubah saat latihan demo | Jalankan ulang `php artisan migrate:fresh --seed` |
