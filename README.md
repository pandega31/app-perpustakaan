# Aplikasi Praktikum Web Laravel

Aplikasi ini merupakan proyek berbasis framework Laravel yang bertujuan untuk mempelajari konsep dasar pengembangan web modern, seperti routing, controller, view, dan struktur MVC. Aplikasi ini digunakan sebagai media latihan praktikum dalam membangun aplikasi web yang modular dan terstruktur.

Studi kasus yang dibangun selama praktikum adalah **Perpustakaan Digital Kampus**: aplikasi manajemen buku, kategori, anggota, dan peminjaman, lengkap dengan autentikasi berbasis peran, REST API, serta dashboard dan laporan yang mengonsumsi API milik aplikasi sendiri.

<!--
Penjelasan Perbedaan Model, View, dan Controller (MVC):
Model bertanggung jawab mengelola logika data dan interaksi dengan database. View bertugas menampilkan antarmuka dan format tampilan informasi kepada pengguna. Controller bertindak sebagai jembatan yang menerima permintaan pengguna, memproses logika aplikasi melalui Model, lalu menentukan View mana yang harus ditampilkan.
-->

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

# Perpustakaan Digital Kampus

## Fitur

- Autentikasi petugas (login/logout) dengan dua peran: **admin** dan **petugas**
- CRUD **Buku** (dengan kategori, pencarian, dan pagination)
- CRUD **Kategori** (khusus admin)
- CRUD **Anggota**
- **Peminjaman** multi-buku dan proses pengembalian
- **Dashboard** statistik (diambil dari `GET /api/stats`)
- **Laporan Peminjaman** (diambil dari `GET /api/loans`)
- **REST API** untuk books, members, loans, dan stats (lihat [`API.md`](API.md))
- Seeder dan Factory dengan data dummy bergaya Indonesia

## Teknologi

- PHP 8.2+
- Laravel 12
- MySQL (via Laragon)
- Composer
- Postman (untuk pengujian API)

## Prasyarat

Pastikan sudah terpasang:

- PHP 8.2 atau lebih baru
- Composer
- MySQL (atau database lain yang didukung Laravel)
- Git

## Instalasi

### 1. Clone repository

```
git clone <URL-REPOSITORY-ANDA>
cd app-perpustakaan
```

### 2. Install dependency

```
composer install
```

### 3. Salin file environment

```
copy .env.example .env
```

(Di Linux/macOS: `cp .env.example .env`)

### 4. Generate application key

```
php artisan key:generate
```

### 5. Konfigurasi `.env`

Sesuaikan koneksi database, dan pastikan variabel berikut ada:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpustakaan
DB_USERNAME=root
DB_PASSWORD=

# Locale Faker untuk data dummy bergaya Indonesia
APP_FAKER_LOCALE=id_ID

# Base URL instance kedua `php artisan serve` khusus panggilan API internal
# (dipakai Dashboard dan Laporan). Wajib port berbeda dari server utama.
INTERNAL_API_URL=http://127.0.0.1:8011
```

Buat database kosong dengan nama yang sama seperti `DB_DATABASE` sebelum lanjut.

### 6. Migrasi dan isi data dummy

```
php artisan migrate:fresh --seed
```

Perintah ini membuat seluruh tabel lalu mengisi: 3 user, 5 kategori, 20 buku, 15 anggota, dan 10 transaksi peminjaman.

## Menjalankan Aplikasi

Aplikasi ini membutuhkan **dua server** berjalan bersamaan, jadi buka **dua terminal**:

**Terminal 1 - server utama (untuk browser):**

```
php artisan serve --port=8000
```

**Terminal 2 - server khusus API internal:**

```
php artisan serve --port=8011
```

### Kenapa harus dua server?

`php artisan serve` hanya memproses satu request pada satu waktu. Dashboard dan Laporan memanggil API milik aplikasi sendiri lewat HTTP. Jika memakai satu server, request pertama menunggu balasan dari request kedua yang tidak pernah bisa diproses (deadlock). Dengan dua server di port berbeda, keduanya tidak saling mengunci. Di server produksi (Nginx + PHP-FPM) masalah ini tidak muncul.

Jika server kedua tidak menyala, Dashboard menampilkan angka `0` dan Laporan menampilkan tabel kosong, tanpa error.

Buka aplikasi di: `http://127.0.0.1:8000`

## Akun Demo

| Peran | Email | Password |
|-------|-------|----------|
| Admin | `admin@pens.ac.id` | `password` |
| Petugas | `petugas1@pens.ac.id` | `password` |
| Petugas | `petugas2@pens.ac.id` | `password` |

Menu **Kategori** hanya tampil untuk admin.

## REST API

Base URL: `http://127.0.0.1:8000/api`

| Method | URL | Keterangan |
|--------|-----|------------|
| GET | `/api/books` | Daftar buku (filter `judul`, `category_id`) |
| GET | `/api/books/{id}` | Detail buku |
| POST | `/api/books` | Tambah buku |
| PUT | `/api/books/{id}` | Ubah buku |
| DELETE | `/api/books/{id}` | Hapus buku |
| GET | `/api/members` | Daftar anggota |
| GET | `/api/members/{id}` | Detail anggota |
| GET | `/api/loans` | Daftar peminjaman |
| POST | `/api/loans` | Buat peminjaman |
| PUT | `/api/loans/{id}/kembalikan` | Proses pengembalian |
| GET | `/api/stats` | Statistik ringkas |

Dokumentasi lengkap beserta contoh request dan response ada di [`API.md`](API.md). Gunakan header `Accept: application/json` pada setiap request.

## Skenario Demo

Langkah-langkah demo aplikasi ada di [`DEMO.md`](DEMO.md).

## Struktur Penting

```
app/Http/Controllers/          Controller web (Auth, Book, Category, Member, Loan, Dashboard)
app/Http/Controllers/Api/      Controller REST API
app/Http/Resources/            API Resource (BookResource, MemberResource, LoanResource)
database/factories/            Factory (BookFactory, MemberFactory)
database/seeders/              Seeder (User, Category, Book, Member, Loan)
resources/views/               Blade template
resources/views/vendor/pagination/custom.blade.php   View pagination custom
routes/web.php                 Route web
routes/api.php                 Route API
config/services.php            Konfigurasi internal_api
```

## Reset Data

Untuk mengembalikan database ke kondisi demo yang bersih:

```
php artisan migrate:fresh --seed
```

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- *[Vehikl](https://vehikl.com)*
- *[Tighten Co.](https://tighten.co)*
- *[Kirschbaum Development Group](https://kirschbaumdevelopment.com)*
- *[64 Robots](https://64robots.com)*
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- *[Active Logic](https://activelogic.com)*

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
