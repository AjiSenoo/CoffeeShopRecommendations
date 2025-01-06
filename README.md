# Sistem Rekomendasi Cabang _Coffee Shop_

## Deskripsi Proyek
Proyek ini adalah sebuah **sistem rekomendasi cabang kedai kopi** yang bertujuan untuk membantu pelanggan memilih cabang terbaik berdasarkan lokasi mereka, waktu tempuh ke cabang tersebut, dan panjang antrean di cabang tersebut. Sistem ini dibangun menggunakan **CodeIgniter 4** dan menggunakan **OpenRouteService API** untuk menghitung waktu tempuh perjalanan.

---

## Fitur Utama

### 1. Rekomendasi Cabang Terbaik
Sistem memberikan rekomendasi cabang terbaik berdasarkan:
- **Waktu Tempuh**: Jarak dari lokasi pengguna ke cabang (prioritas 70%).
- **Panjang Antrean**: Jumlah antrean saat ini di cabang (prioritas 30%).
- Hanya cabang yang dapat dijangkau dengan kendaraan akan direkomendasikan.

### 2. Peran Admin
Admin bertanggung jawab untuk:
- **Mengelola Panjang Antrean**: Menambah atau mengurangi panjang antrean untuk cabang tertentu.

### 3. Peran Pelanggan
Pelanggan dapat:
- **Menambahkan Antrean**: Menambahkan jumlah antrean pada cabang tertentu.

---

## Teknologi yang Digunakan
- **Framework**: CodeIgniter 4
- **Database**: MySQL
- **API Pihak Ketiga**: OpenRouteService API untuk perhitungan waktu tempuh
- **Tools**: Postman untuk pengujian API

---

## Instalasi

### Prasyarat
- **PHP 8.x**
- **Composer**
- **MySQL Server**

### Langkah-Langkah
1. **Clone repository**:
   ```bash
   git clone <URL-repo-Anda>
   cd <nama-folder-repo>
   ```

2. **Install dependensi dengan Composer**:
   ```bash
   composer install
   ```

3. **Konfigurasi database**:
   - Buka file `.env`.
   - Konfigurasikan detail database Anda:
     ```env
     database.default.hostname = localhost
     database.default.database = nama_database
     database.default.username = root
     database.default.password = password
     database.default.DBDriver = MySQLi
     ```

4. **Migrasi database**:
   Jalankan script untuk membuat tabel:
   ```bash
   php spark migrate
   ```

5. **Jalankan server lokal**:
   ```bash
   php spark serve
   ```

6. **Tes API dengan Postman**:
   - Endpoint untuk rekomendasi cabang: `POST /recommend`
   - Endpoint untuk menambah antrean pelanggan: `POST /customer/add-queue`
   - Endpoint untuk mengelola antrean admin: `POST /admin/manage-queue`

---

## Struktur Database

### Tabel `branches`
| Kolom         | Tipe Data      | Deskripsi                         |
|---------------|----------------|------------------------------------|
| `id`          | INT (Primary)  | ID cabang                         |
| `name`        | VARCHAR(255)   | Nama cabang                       |
| `latitude`    | DECIMAL(10,6)  | Koordinat latitude cabang         |
| `longitude`   | DECIMAL(10,6)  | Koordinat longitude cabang        |
| `queue_length`| INT            | Panjang antrean saat ini          |

### Tabel `users`
| Kolom         | Tipe Data      | Deskripsi                         |
|---------------|----------------|------------------------------------|
| `id`          | INT (Primary)  | ID pengguna                       |
| `username`    | VARCHAR(50)    | Username pengguna                  |
| `password`    | VARCHAR(255)   | Password (hashed)                 |
| `role`        | ENUM(admin/customer)| Peran pengguna (admin/pelanggan) |
| `branch_id`   | INT (Foreign)  | ID cabang (hanya untuk admin)     |

---

## Contoh Penggunaan API

### 1. Rekomendasi Cabang
**Endpoint**: `POST /recommend`

**Body**:
```json
{
    "latitude": -6.88902,
    "longitude": 107.616
}
```

**Response**:
```json
{
    "recommendations": [
        {
            "branch": "Fore Dipatiukur",
            "travel_time": 5.2,
            "queue_length": 10,
            "score": 0.45
        },
        {
            "branch": "Fore Ciumbuleuit",
            "travel_time": 7.8,
            "queue_length": 12,
            "score": 0.58
        }
    ],
    "unreachable_branches": [
        "Fore Botanica"
    ],
    "message": "The following branches are not reachable by car: Fore Botanica"
}
```

### 2. Tambah Antrean oleh Pelanggan
**Endpoint**: `POST /customer/add-queue`

**Body**:
```json
{
    "branch_id": 1,
    "increment": 3
}
```

**Response**:
```json
{
    "message": "Queue updated successfully",
    "queue_length": 18
}
```

### 3. Kelola Antrean oleh Admin
**Endpoint**: `POST /admin/manage-queue`

**Body**:
```json
{
    "action": "subtract",
    "amount": 5
}
```

**Response**:
```json
{
    "message": "Queue updated successfully",
    "queue_length": 10
}
```

---

## Catatan
- API ini memerlukan **API Key OpenRouteService**. Masukkan API key Anda di file Controller.
- Pastikan konfigurasi database Anda sudah benar.

---

## Lisensi
Proyek ini menggunakan lisensi **MIT License**. Anda bebas menggunakan, memodifikasi, dan mendistribusikan proyek ini dengan tetap mencantumkan hak cipta.

---

Terima kasih telah menggunakan sistem rekomendasi cabang kedai kopi! 🚀

