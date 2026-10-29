# Dokumentasi API - Perpustakaan Digital Kampus

Dokumentasi seluruh endpoint REST API pada project `app-perpustakaan` (Pertemuan 9).

- **Base URL:** `http://127.0.0.1:8000/api`
- **Header wajib:** `Accept: application/json`
- **Header untuk POST/PUT:** `Content-Type: application/json`
- **Autentikasi:** tidak ada (seluruh endpoint sengaja dibuat publik pada pertemuan ini)
- **Pagination:** endpoint list menampilkan 10 data per halaman, halaman dipilih lewat `?page=`

---

## Ringkasan Endpoint

| No | Method | URL | Body (jika ada) | Status Sukses | Status Gagal |
|----|--------|-----|-----------------|---------------|--------------|
| 1 | GET | `/api/books` | - (query opsional: `judul`, `category_id`, `page`) | 200 | - |
| 2 | GET | `/api/books/{id}` | - | 200 | 404 |
| 3 | POST | `/api/books` | `judul`, `penulis`, `penerbit`, `tahun_terbit`, `isbn`, `stok`, `category_id` | 201 | 422 |
| 4 | PUT | `/api/books/{id}` | sama seperti POST `/api/books` | 200 | 404, 422 |
| 5 | DELETE | `/api/books/{id}` | - | 200 | 404 |
| 6 | GET | `/api/members` | - (query opsional: `page`) | 200 | - |
| 7 | GET | `/api/members/{id}` | - | 200 | 404 |
| 8 | GET | `/api/loans` | - (query opsional: `page`) | 200 | - |
| 9 | POST | `/api/loans` | `member_id`, `user_id`, `tanggal_pinjam`, `tanggal_kembali`, `book_ids[]` | 201 | 422 |
| 10 | PUT | `/api/loans/{id}/kembalikan` | - | 200 | 404 |
| 11 | GET | `/api/stats` | - | 200 | - |

### Arti Status Code

| Kode | Arti |
|------|------|
| 200 | OK - request berhasil |
| 201 | Created - data baru berhasil dibuat |
| 404 | Not Found - data dengan id tersebut tidak ada |
| 422 | Unprocessable Entity - data yang dikirim gagal validasi |

---

## 1. GET /api/books

Menampilkan daftar buku (10 per halaman) beserta kategorinya.

**Query string (opsional):**

| Parameter | Keterangan |
|-----------|------------|
| `judul` | Pencarian sebagian judul (LIKE), contoh `?judul=laskar` |
| `category_id` | Filter kategori, contoh `?category_id=2` |
| `page` | Nomor halaman |

**Response 200:**

```json
{
    "data": [
        {
            "id": 1,
            "judul": "Laskar Pelangi",
            "penulis": "Rina Wulandari",
            "penerbit": "Bentang Pustaka",
            "tahun_terbit": 2008,
            "isbn": "9786021234567",
            "stok": 5,
            "sampul": null,
            "category": {
                "id": 1,
                "nama_kategori": "Novel"
            },
            "created_at": "2026-10-29T03:15:20.000000Z",
            "updated_at": "2026-10-29T03:15:20.000000Z"
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/books?page=1",
        "last": "http://127.0.0.1:8000/api/books?page=2",
        "prev": null,
        "next": "http://127.0.0.1:8000/api/books?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 2,
        "per_page": 10,
        "to": 10,
        "total": 20
    }
}
```

> Pada contoh di atas `data` dipersingkat menjadi 1 item (aslinya 10 item per halaman) dan `meta.links` tidak ditampilkan.

---

## 2. GET /api/books/{id}

Menampilkan satu buku berdasarkan id.

**Contoh:** `GET /api/books/1`

**Response 200:**

```json
{
    "data": {
        "id": 1,
        "judul": "Laskar Pelangi",
        "penulis": "Rina Wulandari",
        "penerbit": "Bentang Pustaka",
        "tahun_terbit": 2008,
        "isbn": "9786021234567",
        "stok": 5,
        "sampul": null,
        "category": {
            "id": 1,
            "nama_kategori": "Novel"
        },
        "created_at": "2026-10-29T03:15:20.000000Z",
        "updated_at": "2026-10-29T03:15:20.000000Z"
    }
}
```

**Response 404** (contoh `GET /api/books/999`):

```json
{
    "message": "No query results for model [App\\Models\\Book] 999"
}
```

---

## 3. POST /api/books

Membuat buku baru. Validasi memakai `StoreBookRequest`.

**Body (JSON):**

```json
{
    "judul": "Pengantar Laravel",
    "penulis": "Budi Santoso",
    "penerbit": "Andi Publisher",
    "tahun_terbit": 2024,
    "isbn": "9786020000001",
    "stok": 5,
    "category_id": 4
}
```

**Response 201:**

```json
{
    "data": {
        "id": 21,
        "judul": "Pengantar Laravel",
        "penulis": "Budi Santoso",
        "penerbit": "Andi Publisher",
        "tahun_terbit": 2024,
        "isbn": "9786020000001",
        "stok": 5,
        "sampul": null,
        "category": {
            "id": 4,
            "nama_kategori": "Teknologi"
        },
        "created_at": "2026-10-29T08:30:00.000000Z",
        "updated_at": "2026-10-29T08:30:00.000000Z"
    }
}
```

**Response 422** (body kosong `{}`):

```json
{
    "message": "Judul wajib diisi. (and 5 more errors)",
    "errors": {
        "judul": ["Judul wajib diisi."],
        "penulis": ["Penulis wajib diisi."],
        "penerbit": ["Penerbit wajib diisi."],
        "tahun_terbit": ["Tahun terbit wajib diisi."],
        "stok": ["Stok wajib diisi."],
        "category_id": ["Kategori wajib dipilih."]
    }
}
```

---

## 4. PUT /api/books/{id}

Memperbarui data buku. Seluruh field wajib dikirim ulang (kecuali `isbn` yang boleh kosong).

**Contoh:** `PUT /api/books/21`

**Body (JSON):**

```json
{
    "judul": "Pengantar Laravel 12",
    "penulis": "Budi Santoso",
    "penerbit": "Andi Publisher",
    "tahun_terbit": 2025,
    "isbn": "9786020000001",
    "stok": 8,
    "category_id": 4
}
```

**Response 200:**

```json
{
    "data": {
        "id": 21,
        "judul": "Pengantar Laravel 12",
        "penulis": "Budi Santoso",
        "penerbit": "Andi Publisher",
        "tahun_terbit": 2025,
        "isbn": "9786020000001",
        "stok": 8,
        "sampul": null,
        "category": {
            "id": 4,
            "nama_kategori": "Teknologi"
        },
        "created_at": "2026-10-29T08:30:00.000000Z",
        "updated_at": "2026-10-29T08:45:00.000000Z"
    }
}
```

**Response 404** (id tidak ada):

```json
{
    "message": "No query results for model [App\\Models\\Book] 999"
}
```

**Response 422** (contoh `stok` bernilai negatif):

```json
{
    "message": "The stok field must be at least 0.",
    "errors": {
        "stok": ["The stok field must be at least 0."]
    }
}
```

---

## 5. DELETE /api/books/{id}

Menghapus buku berdasarkan id.

**Contoh:** `DELETE /api/books/21`

**Response 200:**

```json
{
    "message": "Buku berhasil dihapus."
}
```

**Response 404** (id tidak ada):

```json
{
    "message": "No query results for model [App\\Models\\Book] 999"
}
```

---

## 6. GET /api/members

Menampilkan daftar anggota (10 per halaman).

**Response 200:**

```json
{
    "data": [
        {
            "id": 1,
            "nama": "Dewi Lestari",
            "nim": "3125600001",
            "email": "dewi.lestari123@pens.ac.id",
            "nomor_telepon": "081234567890",
            "alamat": "Jl. Mawar No. 5, Surabaya",
            "status": "aktif",
            "created_at": "2026-10-29T03:15:22.000000Z",
            "updated_at": "2026-10-29T03:15:22.000000Z"
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/members?page=1",
        "last": "http://127.0.0.1:8000/api/members?page=2",
        "prev": null,
        "next": "http://127.0.0.1:8000/api/members?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 2,
        "per_page": 10,
        "to": 10,
        "total": 15
    }
}
```

> `data` dipersingkat menjadi 1 item.

---

## 7. GET /api/members/{id}

Menampilkan satu anggota berdasarkan id.

**Contoh:** `GET /api/members/1`

**Response 200:**

```json
{
    "data": {
        "id": 1,
        "nama": "Dewi Lestari",
        "nim": "3125600001",
        "email": "dewi.lestari123@pens.ac.id",
        "nomor_telepon": "081234567890",
        "alamat": "Jl. Mawar No. 5, Surabaya",
        "status": "aktif",
        "created_at": "2026-10-29T03:15:22.000000Z",
        "updated_at": "2026-10-29T03:15:22.000000Z"
    }
}
```

**Response 404** (contoh `GET /api/members/999`):

```json
{
    "message": "No query results for model [App\\Models\\Member] 999"
}
```

---

## 8. GET /api/loans

Menampilkan daftar transaksi peminjaman (10 per halaman) lengkap dengan anggota, petugas, dan daftar buku.

**Response 200:**

```json
{
    "data": [
        {
            "id": 1,
            "member": {
                "id": 3,
                "nama": "Agus Prasetyo"
            },
            "petugas": {
                "id": 2,
                "name": "Siti Rahmawati"
            },
            "tanggal_pinjam": "2026-10-10",
            "tanggal_kembali": "2026-10-17",
            "tanggal_dikembalikan": null,
            "status": "dipinjam",
            "books": [
                {
                    "id": 4,
                    "judul": "Bumi Manusia"
                },
                {
                    "id": 9,
                    "judul": "Algoritma dan Struktur Data"
                }
            ]
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/loans?page=1",
        "last": "http://127.0.0.1:8000/api/loans?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "per_page": 10,
        "to": 10,
        "total": 10
    }
}
```

> `data` dipersingkat menjadi 1 item.

---

## 9. POST /api/loans

Membuat transaksi peminjaman baru. Satu transaksi bisa mencakup banyak buku lewat `book_ids[]`. `user_id` (petugas) wajib dikirim karena API tidak memakai session login.

**Body (JSON):**

```json
{
    "member_id": 1,
    "user_id": 1,
    "tanggal_pinjam": "2026-10-29",
    "tanggal_kembali": "2026-11-05",
    "book_ids": [1, 2]
}
```

**Response 201:**

```json
{
    "data": {
        "id": 11,
        "member": {
            "id": 1,
            "nama": "Dewi Lestari"
        },
        "petugas": {
            "id": 1,
            "name": "Bambang Sutrisno"
        },
        "tanggal_pinjam": "2026-10-29",
        "tanggal_kembali": "2026-11-05",
        "tanggal_dikembalikan": null,
        "status": "dipinjam",
        "books": [
            {
                "id": 1,
                "judul": "Laskar Pelangi"
            },
            {
                "id": 2,
                "judul": "Garudayana"
            }
        ]
    }
}
```

**Response 422** (body kosong `{}`):

```json
{
    "message": "Anggota wajib dipilih. (and 4 more errors)",
    "errors": {
        "member_id": ["Anggota wajib dipilih."],
        "user_id": ["Petugas (user_id) wajib diisi."],
        "tanggal_pinjam": ["Tanggal pinjam wajib diisi."],
        "tanggal_kembali": ["Tanggal kembali wajib diisi."],
        "book_ids": ["Pilih minimal satu buku."]
    }
}
```

---

## 10. PUT /api/loans/{id}/kembalikan

Menandai peminjaman sebagai sudah dikembalikan. `status` menjadi `dikembalikan` dan `tanggal_dikembalikan` terisi tanggal hari ini. Tidak membutuhkan body.

**Contoh:** `PUT /api/loans/11/kembalikan`

**Response 200:**

```json
{
    "data": {
        "id": 11,
        "member": {
            "id": 1,
            "nama": "Dewi Lestari"
        },
        "petugas": {
            "id": 1,
            "name": "Bambang Sutrisno"
        },
        "tanggal_pinjam": "2026-10-29",
        "tanggal_kembali": "2026-11-05",
        "tanggal_dikembalikan": "2026-10-29",
        "status": "dikembalikan",
        "books": [
            {
                "id": 1,
                "judul": "Laskar Pelangi"
            },
            {
                "id": 2,
                "judul": "Garudayana"
            }
        ]
    }
}
```

**Response 404** (contoh `PUT /api/loans/999/kembalikan`):

```json
{
    "message": "No query results for model [App\\Models\\Loan] 999"
}
```

---

## 11. GET /api/stats

Menampilkan ringkasan statistik perpustakaan. Endpoint ini dikonsumsi oleh halaman Dashboard (Pertemuan 10).

**Response 200:**

```json
{
    "total_buku": 20,
    "total_anggota": 15,
    "peminjaman_aktif": 4
}
```
