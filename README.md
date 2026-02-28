# Super Framework
The lightweight and fastest PHP framework from the creator of CRUDBooster. Now upgraded to v2.0.0 with modern architecture and high security.

# Why Super Framework?
Kita mengadopsi beberapa pola pada framework Laravel dan sekaligus merangkum apa saja yang paling essensial dalam development web. Dengan engine v2.0.0, kami memaksimalkan performa, keamanan, dan kemudahan testing tanpa mengorbankan kecepatan.

Daftar Isi
=================
* [Instalasi](#instalasi)
* [Memulai](#memulai)
  * [Konfigurasi Environment](#konfigurasi-environment-env)
  * [Struktur Folder](#struktur-folder)
* [Arsitektur & Design Patterns](#arsitektur--design-patterns)
  * [Dependency Injection (DI) Container](#dependency-injection-di-container)
  * [Repository Pattern](#repository-pattern)
  * [Strategy Pattern](#strategy-pattern)
* [Keamanan (Security)](#keamanan)
  * [Secure Headers](#secure-headers)
  * [CSRF Protection](#csrf-protection)
  * [Rate Limiting](#rate-limiting)
* [Performa & Optimasi](#performa--optimasi)
  * [Redis Caching](#redis-caching)
  * [Asset Minification](#asset-minification)
* [Testing (PHPUnit)](#testing)
* [Controller & Routing](#controller--routing)
* [CLI (super)](#cli-super)
* [Database ORM](#database-orm)
* [Contact](#contact)

# Instalasi

### Syarat Kebutuhan Sistem
Sebelum melakukan instalasi pastikan sistem Anda sudah memenuhi persyaratan berikut ini:
- **PHP ^8.0** (Direkomendasikan 8.1+)
- Web server Apache / Nginx
- MySQL / MariaDB / Postgre / SQL Server / SQLite
- **Redis** (Opsional, untuk caching & rate limiting)
- Composer
- PDO Extension

### Perintah Instalasi
```bash
$ composer create-project superframework/superframework my_new_super
```

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Memulai
## Konfigurasi Environment (.env)
Copy file `.env.example` menjadi `.env`:
```bash
$ cp .env.example .env 
```
Konfigurasi penting pada engine v2.0.0:
```bash
APP_NAME="SUPER FRAMEWORK"
DISPLAY_ERRORS=true
LOGGING_ERRORS=false

# Database Configuration
DB_DRIVER=mysql # mysql, pgsql, sqlsrv, sqlite
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=superframework
DB_USERNAME=root
DB_PASSWORD=

# Redis Configuration (Untuk Performa & Rate Limit)
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null
```

## Struktur Folder
```bash
/app
    /Contracts      # Interface untuk Strategy/Service
    /Helpers        # Helper kustom (General.php)
    /Middleware     # Security, CSRF, RateLimit
    /Modules        # Modular logic (Controllers, Views, Configs)
    /Providers      # DI Container Registration (AppServiceProvider)
    /Repositories   # Data Access Layer
    /Services       # Business Logic Layer
/configs            # Konfigurasi aplikasi
/tests              # Unit & Integration Tests (PHPUnit)
/public             # Entry point (index.php) & Assets
/tasks              # Cron jobs / Scheduler
```

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Arsitektur & Design Patterns
Super Framework v2.0.0 mengimplementasikan pola arsitektur modern untuk memudahkan skalabilitas dan testing.

### Dependency Injection (DI) Container
Seluruh dependensi dikelola secara otomatis melalui Container. Registrasi dilakukan di `app/Providers/AppServiceProvider.php`.
```php
$container->singleton(UserRepository::class, function ($app) {
    return new UserRepository($app->make(ORM::class));
});
```

### Repository Pattern
Memisahkan logika akses database dari controller.
- **BaseRepository**: Implementasi CRUD dasar.
- **UserRepository**: Logika spesifik untuk tabel users.

### Strategy Pattern
Digunakan untuk logika bisnis yang dapat berubah-ubah (interchangeable).
- Lihat contoh pada `app/Contracts/WelcomeStrategyInterface.php`.

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Keamanan
Keamanan adalah prioritas utama pada versi terbaru ini.

### Secure Headers
Menggunakan `SecurityMiddleware` untuk mengaktifkan:
- X-XSS-Protection
- X-Frame-Options (SameOrigin)
- Content-Security-Policy (CSP)
- HSTS & NoSniff

### CSRF Protection
Validasi token otomatis pada request POST/PUT/DELETE melalui `CSRFMiddleware`. Gunakan helper `csrf_input()` pada form Anda.

### Rate Limiting
Melindungi endpoint API dari abuse menggunakan `RateLimitMiddleware` berbasis Redis. Default: 60 request/menit per IP.

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Performa & Optimasi

### Redis Caching
Implementasi `RedisService` memungkinkan Anda menyimpan data berat di memory untuk akses instan.
```php
$redis->set('user_profile_1', $data, 3600);
```

### Asset Minification
Helper `minify_asset()` di `app/Helpers/General.php` membantu mengecilkan ukuran file CSS/JS secara runtime untuk loading page yang lebih cepat.

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Testing
Kami menyediakan suite testing lengkap menggunakan PHPUnit dengan target coverage 90-95%.

Jalankan test:
```bash
./vendor/bin/phpunit
```
Cek coverage (butuh Xdebug):
```bash
XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-text
```

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# CLI (super)
| Command | Description |
| ------- | ----------- |
| `php super compile` | Generate cache routing & config (Wajib dijalankan setelah ubah route) |
| `php super package:discover` | Discover modul & plugin baru |
| `php super make:migration {name}` | Buat file migrasi database |
| `php super migrate` | Jalankan migrasi ke database |

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Standard Coding (PSR-12)
Project ini secara ketat mengikuti standar PSR-12. Gunakan perintah berikut untuk memperbaiki formatting secara otomatis:
```bash
./vendor/bin/php-cs-fixer fix
```

---
## Support & Donation
Hi thanks for using my open source project, you could support me via :
[https://saweria.co/ferryariawan](https://saweria.co/ferryariawan)
or via [https://buymeacoffee.com/ferryariawan](https://buymeacoffee.com/ferryariawan)

# Contact
Laporan keamanan / celah / security dapat Anda kirimkan ke *ferdevelop15@gmail.com*
