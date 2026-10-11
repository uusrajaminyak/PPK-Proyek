# Fasilita FSM — Sistem Reservasi & Pelaporan Fasilitas Fakultas Sains dan Matematika

![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP Version](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15.0-316192?style=for-the-badge&logo=postgresql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

Fasilita FSM adalah aplikasi berbasis web untuk mengelola peminjaman atau reservasi dan pelaporan kerusakan fasilitas milik Fakultas Sains dan Matematika (FSM), Universitas Diponegoro. Cakupan layanan ini terbatas pada fasilitas di lingkungan FSM, seperti ruang kelas, aula, laboratorium, peralatan, dan lapangan fakultas.

---

## Fitur Utama Sistem

### 1. Modul Pengunjung & Pengguna Terdaftar (Mahasiswa/Dosen/Staf)
* **Katalog & Ketersediaan Real-Time**: Merekap daftar fasilitas beserta status ketersediaan per slot waktu 30 menit tanpa menampilkan identitas pemohon lain demi privasi.
* **Pengajuan Reservasi**: Pengajuan peminjaman fasilitas pada jam operasional (07.00–20.00 WIB) dengan durasi kelipatan 30 menit dan validasi ganda (Client & Server side).
* **Pembatalan Mandiri 24 Jam**: Pengguna dapat membatalkan reservasi miliknya maksimal 24 jam sebelum `start_time`.
* **Riwayat & Status Reservasi**: Pemantauan status persetujuan reservasi secara transparan.
* **Pelaporan Kerusakan Fasilitas**: Pengajuan laporan kerusakan fasilitas dengan bukti foto (Maks. 3 foto, @2MB, format JPG/PNG/WEBP).

### 2. Modul Petugas Operasional
* **Dashboard Antrean Terpusat**: Pemrosesan antrean reservasi dan laporan masuk secara real-time.
* **Persetujuan & Anti-Bentrok Jadwal**: Verifikasi manual persetujuan reservasi dengan proteksi otomatis di tingkat backend untuk mencegah schedule collision.
* **Pembatalan Mendesak**: Pembatalan reservasi yang telah disetujui akibat kondisi darurat dengan pencatatan alasan resmi.
* **Manajemen Kerusakan & Status Fasilitas**: Pembaruan status laporan (Baru, Diproses, Selesai, Ditolak) dan penandaan fasilitas berstatus `'Dalam Perbaikan'` yang secara otomatis memicu peringatan pembatalan pada reservasi terdampak.

### 3. Modul Administrator
* **Registrasi Terkontrol & Verifikasi Akun**: Pendaftaran akun petugas langsung oleh Admin (mencegah registrasi mandiri di level backend) dan verifikasi/penolakan registrasi mandiri akun pengguna.
* **Manajemen Data Fasilitas & Soft Delete**: Pengelolaan data master fasilitas menggunakan mekanisme *Soft Delete* agar integritas riwayat transaksi tetap terjaga.
* **Dashboard Analitik Zero-Scroll & Ekspor Laporan**: Visualisasi rekapitulasi okupansi dan frekuensi kerusakan fasilitas dengan fitur ekspor konsolidasi multi-format (**CSV**, **XLSX XML**, dan **PDF Print Engine**).

---

## System Requirements

* **Docker & Docker Compose**

---

## Panduan Instalasi

* 1. Clone the Repository
```bash
git clone https://github.com/uusrajaminyak/PPK-Proyek.git
```

* 2. Copy the environment file
```bash
cp .env.example .env
```

* 3. Build the containers
```bash
docker compose up -d --build
```

* 4. Install the dependencies
```bash
docker compose exec app composer install
docker compose exec app npm install
docker compose exec app npm run build
```

* 5. Generate the application key
```bash
docker compose exec app php artisan key:generate
```

* 6. Configure storage folder access and cache
```bash
docker compose exec -u 0 app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
docker compose exec -u 0 app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
```

* 7. Database migration and links
```bash
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan storage:link
```

## Testing Accounts
```bash
Role: Mahasiswa / Pengguna Publik (Akses Halaman Utama)
Email: ahmed@student.undip.ac.id
Password: password
Nama: Ahmed Fauzi (HMTI)
NIM / Identitas: 21120122140089
Kategori: Mahasiswa

Role: Administrator (Akses Dashboard Admin)
Email: admin@undip.ac.id
Password: password
Nama: Admin Utama
NIP / Identitas: 19850912201012
Kategori: Staf / Admin

Role: Petugas / Staff (Akses Dashboard Petugas)
Email: budi.santoso@staff.undip.ac.id
Password: password
Nama: Budi Santoso (PTG-001)
```

## Daily Runs

* 1. Activate the containers
```bash
docker compose up -d
```

* 2. Access the localhost
```bash
localhost:8000
```

* 3. Shutting down the containers
```bash
docker compose down
```
