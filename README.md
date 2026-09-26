# BookEase

**Sistem Booking Barbershop / Salon** berbasis web — fullstack dengan Laravel & Filament.

Customer bisa memilih layanan, staff, dan slot jam yang tersedia. Admin mengelola data dan status booking lewat panel Filament.

---

## Demo Accounts

| Role     | Email                  | Password   |
|----------|------------------------|------------|
| Admin    | `admin@bookease.test`  | `password` |
| Customer | `customer@example.com` | `password` |

- **Frontend:** `/`
- **Admin Panel:** `/admin`

---

## Fitur

### Customer
- Katalog layanan (harga & durasi)
- Pilih satu atau lebih layanan
- Pilih staff dan tanggal
- **Slot jam tersedia otomatis** (cek jam kerja staff + bentrok booking)
- Riwayat booking & halaman detail
- Batalkan booking (hanya status `pending`)
- Email konfirmasi saat booking berhasil
- Email notifikasi saat status diubah admin

### Admin (`/admin`)
- CRUD **Layanan**, **Staff**, **Jadwal**, **Booking**
- Filter booking (status, rentang tanggal, staff)
- Relation Manager: daftar layanan per booking
- Tombol cepat **Confirm / Complete / Cancel** di tabel
- Dashboard: statistik + daftar booking terbaru
- Akses terbatas user dengan `is_admin = true`

### Status Booking
`pending` → `confirmed` → `completed`  
(atau `cancelled` dari pending/confirmed)

---

## Tech Stack

| Layer        | Teknologi                          |
|--------------|------------------------------------|
| Backend      | Laravel 12 / 13, PHP 8.3+          |
| Admin Panel  | Filament v5                        |
| Auth         | Laravel Breeze (Blade)             |
| Frontend     | Blade, Tailwind CSS, Alpine.js, Vite |
| Database     | MySQL / PostgreSQL / SQLite        |
| Mail         | Laravel Mailable (Markdown)        |

---

## Instalasi

### Requirements
- PHP 8.3+
- Composer
- Node.js & npm
- Database (MySQL / SQLite / PostgreSQL)

### Langkah

```bash
# 1. Clone repository
git clone https://github.com/USERNAME/bookease.git
cd bookease

# 2. Install dependency PHP
composer install

# 3. Environment
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env
# Contoh SQLite (paling sederhana):
# DB_CONNECTION=sqlite
# (buat file: touch database/database.sqlite)

# Contoh MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=bookease
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Migrasi & seeder
php artisan migrate --seed

# 6. Frontend assets
npm install
npm run build

# 7. Jalankan server
php artisan serve
```

Buka:
- http://localhost:8000 — aplikasi customer  
- http://localhost:8000/admin — panel admin  

Untuk development dengan hot reload:

```bash
npm run dev
# terminal lain:
php artisan serve
```

---

## Konfigurasi Email

### Development (log ke file)

```env
MAIL_MAILER=log
```

Isi email muncul di `storage/logs/laravel.log`.

### Production / testing (contoh SMTP)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@bookease.test"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Struktur Project

```
app/
├── Filament/
│   ├── Resources/
│   │   ├── Bookings/      # Resource + Relation Manager layanan
│   │   ├── Schedules/
│   │   ├── Services/
│   │   └── Staff/
│   └── Widgets/
│       ├── StatsOverview.php      # Statistik dashboard
│       └── LatestBookings.php     # Tabel booking terbaru
├── Http/Controllers/
│   └── BookingController.php      # Alur booking + slot jam
├── Mail/
│   ├── BookingCreated.php         # Email booking baru
│   └── BookingStatusUpdated.php   # Email ubah status
└── Models/
    ├── Booking.php
    ├── Schedule.php
    ├── Service.php
    ├── Staff.php
    └── User.php                   # is_admin + canAccessPanel

database/
├── migrations/
└── seeders/
    ├── UserSeeder.php
    ├── ServiceSeeder.php
    ├── StaffSeeder.php
    └── ScheduleSeeder.php

resources/views/
├── booking/                       # UI customer
│   ├── index.blade.php            # Pilih layanan
│   ├── create.blade.php           # Pilih staff & jadwal
│   ├── history.blade.php
│   └── show.blade.php
└── emails/                        # Template email Markdown
```

---

## Database (Ringkas)

| Tabel            | Keterangan                                      |
|------------------|-------------------------------------------------|
| `users`          | Customer & admin (`is_admin`)                   |
| `services`       | Layanan (nama, harga, durasi)                   |
| `staff`          | Barber / staff                                  |
| `schedules`      | Jadwal kerja staff per hari                     |
| `bookings`       | Transaksi booking                               |
| `booking_service`| Pivot layanan + harga/durasi saat booking       |

---

## Alur Booking (Customer)

1. Pilih layanan di halaman utama  
2. Pilih staff + tanggal  
3. Sistem menampilkan **jam yang masih kosong** (interval 30 menit, sesuai durasi layanan & jadwal staff)  
4. Submit → status `pending` + email konfirmasi  
5. Admin konfirmasi / selesaikan / batalkan → customer dapat email status  

---

## Screenshots

> Tambahkan screenshot di sini setelah deploy / running lokal:

- Halaman pilih layanan  
- Form pilih staff & jam  
- Riwayat booking  
- Dashboard admin  
- Tabel booking + tombol status  

```markdown
## Screenshots

### Customer
![Layanan](docs/screenshots/services.png)
![Booking](docs/screenshots/booking-form.png)

### Admin
![Dashboard](docs/screenshots/admin-dashboard.png)
![Bookings](docs/screenshots/admin-bookings.png)
```

---

## Scripts Berguna

```bash
php artisan migrate:fresh --seed   # Reset DB + data demo
php artisan filament:optimize      # Cache Filament (production)
npm run build                      # Build assets production
php artisan queue:work             # Jika nanti email di-queue
```

---

## Roadmap / Pengembangan Lanjutan

- [ ] Integrasi payment (Midtrans / Xendit)
- [ ] Notifikasi WhatsApp
- [ ] Multi-cabang / outlet
- [ ] Rating & review setelah completed
- [ ] Calendar view di admin
- [ ] API untuk mobile app

---

## License

MIT License — bebas digunakan untuk belajar dan portfolio.

---

## Author

Dibangun sebagai project belajar **Fullstack Laravel + Filament**.

Kalau bermanfaat, berikan ⭐ di repository ini.
