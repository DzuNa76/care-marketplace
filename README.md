# Care Marketplace

Platform marketplace layanan perawatan dan bantuan rumah tangga yang menghubungkan pelanggan dengan penyedia jasa individu di Indonesia.

## 1. Deskripsi Proyek

Care Marketplace merupakan aplikasi berbasis web yang mempertemukan pelanggan dengan penyedia layanan:

- Caregiver
- Babysitter
- Perawat
- Cleaning Service

Wilayah pilot awal: **Malang Raya** (Kota Malang, Kabupaten Malang, dan Kota Batu).

Pelanggan dapat mencari penyedia layanan dan mengajukan pemesanan. Penyedia layanan dapat menerima atau menolak permintaan pemesanan.

Platform dirancang menggunakan arsitektur Laravel Modular Monolith agar pengembangan dan pemeliharaan lebih mudah.

## 2. Teknologi

| Teknologi | Keterangan |
|---|---|
| PHP 8.5 | Backend |
| Laravel 13 | Framework utama |
| MySQL | Database |
| Filament 5 | Dashboard administrator |
| Livewire 4 | Antarmuka interaktif |
| Blade | Template frontend |
| Spatie Laravel Permission | Role dan permission |
| Composer | Dependency manager PHP |
| Node.js & NPM | Dependency frontend |

## 3. Fitur dan Progres

### Sudah dibangun

- Instalasi Laravel 13.
- Koneksi database MySQL.
- Dashboard administrator Filament.
- Sistem role menggunakan Spatie Permission.
- Role customer, provider, dan admin.
- Profil customer.
- Profil provider.
- Kategori layanan.
- Master keterampilan berdasarkan kategori.
- Data layanan provider.
- Paket harga layanan.
- Relasi keterampilan dengan layanan provider.
- Seeder untuk role, kategori, dan keterampilan.

### Tahap pengembangan berikutnya

- Verifikasi dokumen provider.
- Manajemen lokasi layanan.
- Dashboard customer dan provider.
- Pencarian dan filter penyedia layanan.
- Sistem booking.
- Payment gateway.
- Komisi platform dan pencairan dana.
- Rating dan ulasan.
- Notifikasi.
- Audit log dan peningkatan keamanan.

## 4. Persyaratan Instalasi

Pastikan perangkat sudah memiliki:

- PHP 8.5 atau versi yang kompatibel dengan proyek.
- Composer.
- MySQL.
- Node.js dan NPM.
- Git.
- Visual Studio Code (disarankan).

Ekstensi PHP yang dibutuhkan mengikuti persyaratan Laravel 13 dan dependency proyek.

## 5. Cara Clone Proyek

Buka terminal atau Git Bash, kemudian jalankan:

```bash
git clone https://github.com/USERNAME/care-marketplace.git
```

Masuk ke direktori proyek:

```bash
cd care-marketplace
```

> Ganti URL repository dengan alamat repository GitHub yang sebenarnya.

## 6. Instalasi Dependency

Install dependency PHP:

```bash
composer install
```

Install dependency frontend:

```bash
npm install
```

## 7. Konfigurasi Environment

Salin file environment:

Windows CMD:

```cmd
copy .env.example .env
```

PowerShell:

```powershell
Copy-Item .env.example .env
```

Buat application key:

```bash
php artisan key:generate
```

## 8. Konfigurasi Database

Buat database MySQL dengan nama:

```sql
CREATE DATABASE care_marketplace;
```

Buka file `.env`, kemudian sesuaikan konfigurasi:

```env
APP_NAME="Care Marketplace"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=care_marketplace
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan username dan password MySQL dengan konfigurasi komputer masing-masing.

## 9. Migrasi dan Seeder

Jalankan migrasi database:

```bash
php artisan migrate
```

Jalankan seeder master data:

```bash
php artisan db:seed
```

Seeder akan mengisi:

- 3 role utama: customer, provider, admin.
- 4 kategori layanan.
- 29 keterampilan berdasarkan kategori.

**Catatan:** Jangan menggunakan `php artisan migrate:fresh` pada database yang berisi data penting karena perintah tersebut menghapus seluruh tabel dan membuat ulang database.

## 10. Membuat Akun Administrator

Setelah migrasi dan seeder selesai, jalankan:

```bash
php artisan tinker
```

Buat akun administrator:

```php
$user = App\Models\User::create([
    'name' => 'Administrator',
    'email' => 'admin@example.com',
    'password' => 'GantiPasswordKuat123!',
]);
```

Berikan role admin:

```php
$user->assignRole('admin');
```

Verifikasi role:

```php
$user->getRoleNames();
```

Jika hasilnya memuat `admin`, akun tersebut sudah memiliki hak akses administrator.

Keluar dari Tinker:

```php
exit
```

Gunakan password yang kuat dan ganti password contoh sebelum digunakan.

## 11. Menjalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Jalankan frontend development server jika diperlukan:

```bash
npm run dev
```

Buka aplikasi melalui:

```text
http://127.0.0.1:8000
```

Dashboard administrator Filament:

```text
http://127.0.0.1:8000/admin
```

Login menggunakan akun administrator yang telah dibuat.

## 12. Struktur Direktori Penting

```text
care-marketplace/
├── app/
│   ├── Models/
│   ├── Filament/
│   └── Providers/
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── public/
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
├── routes/
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md
```

## 13. Catatan Keamanan

- Jangan mengunggah file `.env` ke repository publik.
- Jangan menyimpan password atau API key di dalam source code.
- Dokumen identitas dan dokumen verifikasi provider harus menggunakan private storage.
- Hak akses harus diperiksa di backend, bukan hanya disembunyikan di tampilan.
- Role admin tidak boleh dapat diberikan melalui registrasi publik.
- Dokumen kompetensi perawat harus diverifikasi sebelum layanan profesional diaktifkan.
- Jangan menghapus data penting menggunakan perintah migrasi yang bersifat destruktif.

## 14. Status Pengembangan

**Status:** Development

**Platform:** Web Application

**Arsitektur:** Laravel Modular Monolith

**Target wilayah awal:** Malang Raya

**Pengembang:** Solo Developer

README ini akan diperbarui seiring perkembangan fitur dan perubahan struktur aplikasi.

---

*Care Marketplace — Connecting People with Trusted Care Services.*

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
