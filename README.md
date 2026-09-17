# Sistem Informasi Sanggar Seni Jiwa Etnik Blambangan (JEB)

Portal informasi dan manajemen digital untuk Sanggar Seni Jiwa Etnik Blambangan (JEB), sebuah organisasi seni di Kecamatan Singojuruh, Kabupaten Banyuwangi, yang berfokus pada pelestarian budaya lokal dan pengembangan kreativitas generasi muda melalui seni tradisional Banyuwangi.

Sebelumnya, dokumentasi dan informasi sanggar tersebar di berbagai platform media sosial yang tidak terintegrasi, sehingga menyulitkan pengelolaan dan mempersempit jangkauan promosi. Sistem ini dibangun untuk menjadi satu portal terpadu yang menjawab tiga permasalahan utama: keterbatasan media promosi digital, risiko pencurian dokumentasi karya seni, dan pengelolaan jadwal/kostum yang tidak efisien.

## Fitur-Fitur

**Untuk Pengunjung (Publik)**
- Landing page profil sanggar (visi, misi, sejarah, sambutan pembina, data pengurus & pelatih)
- Berita & publikasi (pengumuman, prestasi, kolaborasi, event)
- Kalender event interaktif (latihan, pertunjukan, pelatihan)
- Katalog penyewaan kostum dengan pencarian, filter, dan status ketersediaan real-time
- Galeri foto kegiatan sanggar yang terorganisir per kategori

**Untuk Admin**
- Dashboard admin
- Kelola berita (CRUD)
- Kelola event (CRUD)
- Kelola profil sanggar & data personel (pengurus/pelatih)
- Pengaturan akun (ubah profil & password)

**Untuk Superadmin**
- Seluruh fitur admin, ditambah:
- Kelola akun admin (CRUD)
- Kelola katalog kostum (CRUD)

**Keamanan**
- Autentikasi & otorisasi berbasis Role-Based Access Control (RBAC) — superadmin, admin, user
- Login dengan reset password melalui verifikasi kode OTP via email
- Sanitasi input HTML menggunakan HTMLPurifier untuk mencegah XSS
- Query database menggunakan parameter binding (PDO) untuk mencegah SQL Injection
- Digital watermarking otomatis pada setiap foto yang diunggah (menggunakan Intervention Image) untuk melindungi hak cipta dokumentasi karya seni
- Pengujian keamanan menyeluruh: SAST, DAST, dan manual penetration testing

## Tech Stack

| Kategori | Teknologi |
|---|---|
| Backend Framework | Laravel (PHP 8.2), arsitektur MVC |
| Database | MySQL (Eloquent ORM) |
| Templating | Blade |
| Frontend | HTML, CSS, JavaScript (Blade views) |
| Pengolahan Gambar | Intervention Image (watermarking) |
| Sanitasi HTML | HTMLPurifier |
| Editor Konten | TinyMCE |
| Desain UI/UX | Figma |
| Keamanan Akses | RBAC (Role-Based Access Control) |

## Panduan Instalasi & Menjalankan Aplikasi

### Prasyarat
- PHP 8.2
- Composer
- Node.js & NPM
- MySQL
- Git

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone <url-repository-ini>
   cd <nama-folder-proyek>
   ```

2. **Install dependency PHP**
   ```bash
   composer install
   ```

3. **Install dependency JavaScript**
   ```bash
   npm install
   ```

4. **Salin file environment**
   ```bash
   cp .env.example .env
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Konfigurasi database**

   Buat database baru di MySQL, lalu sesuaikan kredensialnya pada file `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sanggar_jeb
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. **Konfigurasi akun seeder (wajib)**

   Seeder akun superadmin/admin dan konfigurasi OTP diambil dari environment variable, sehingga perlu ditambahkan ke `.env` sebelum migrasi:
   ```
   SUPERADMIN_NAME=Nama Superadmin
   SUPERADMIN_EMAIL=superadmin@example.com
   SUPERADMIN_PASSWORD=password

   ADMIN_NAME=Nama Admin
   ADMIN_EMAIL=admin@example.com
   ADMIN_PASSWORD=password

   OTP_NAME=Nama Akun OTP
   OTP_EMAIL=otp@example.com
   OTP_PASSWORD=password
   ```

   Tambahkan juga konfigurasi mail (SMTP) di `.env` agar fitur kirim OTP via email bisa berfungsi.

8. **Jalankan migrasi & seeder**
   ```bash
   php artisan migrate --seed
   ```

9. **Buat symbolic link storage** (untuk menampilkan gambar yang diunggah)
   ```bash
   php artisan storage:link
   ```

10. **Build asset frontend**
    ```bash
    npm run build
    ```

11. **Jalankan server lokal**
    ```bash
    php artisan serve
    ```
    ```bash
    nom run dev
    ```
    Aplikasi dapat diakses melalui `http://127.0.0.1:8000`.

### Akun Demo

Akun superadmin dan admin **tidak di-hardcode**, melainkan dibuat dari nilai yang diisi pada file `.env` (lihat langkah 7 di atas). Gunakan email & password yang telah dikonfigurasi pada `.env` untuk login sebagai superadmin/admin setelah seeding selesai.

## Struktur Folder

```
app/
 ├── Http/Controllers/      # Controller (Admin, Superadmin, Auth, Umum, dll.)
 └── Models/                # Model Eloquent (User, Berita, Event, Katalog, Galeri, Personel, dll.)
database/
 ├── migrations/            # Skema tabel database
 └── seeders/                # Seeder data awal (akun superadmin/admin, profil, dll.)
resources/
 └── views/                 # Tampilan Blade (landing page, dashboard admin/superadmin, auth)
routes/
 └── web.php                # Definisi route aplikasi
public/                     # Asset publik (gambar, storage link, dsb.)
```

## Anggota Kelompok

**Kelompok 5 — AAH Team** · TRPL2D · Semester 4 · 2026

| Nama | NIM | Peran |
|---|---|---|
| Heri Herlambang | 362458302092 | Ketua Tim |
| Dino Febiyan | 362458302043 | Quality Assurance |
| Cheryl Aurellya B.J | 362458302037 | Front End |
| Febriyan Putra H. | 362458302042 | Back End |
| Rizky Bintang P. | 362458302091 | UI/UX |

## Catatan Tambahan

- Metode pengembangan: Scrum, dengan 5 sprint (analisis kebutuhan & desain, autentikasi & layout, desain mobile/profil/kalender, backend core & portal berita, katalog/watermarking & QA).
- Total cakupan: 18 use case, 5 fitur utama.

