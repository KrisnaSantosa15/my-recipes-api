<!-- <p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p> -->

## Preparation

Untuk bisa menginstall aplikasi ini, diperlukan beberapa tools.

-   **PHP**: Versi 8.1 atau yang lebih baru.
-   **Apache**: Versi 2.4 atau yang lebih baru.
-   **MySQL**: Versi 5.7 atau yang lebih baru.
-   **Composer**: Versi 1.10 atau yang lebih baru.

Jika ingin menginstall semua tools tersebut sekaligus (Kecuali Composer), silahkan gunakan Laragon atau Xampp.

## Instalasi

1. Clone repository ini dengan menggunakan command line:

```
git clone https://github.com/KrisnaSantosa15/my-recipes-api.git
```

2. Buka terminal dan masuk ke folder yang telah di clone.
3. Install packages laravel yang dibutuhkan dengan command:

```
composer install
```

4. Siapkan database dengan nama my_recipes_api. (Gunakan HeidiSQL, phpMyadmin atau Database client lainnya)
5. Masih pada folder project my-recipes-api, Copy file .env.example ke .env dengan command:

```
cp .env.example .env
```

6. Generate key untuk .env dengan command:

```
php artisan key:generate
```

7. Konfigurasi database, url dan pastikan bahwa key sudah ada pada file .env, seperti contoh di bawah ini:

```diff
APP_NAME=Laravel
APP_ENV=local
+APP_KEY=base64:ThVlQSQANNHyityhzZFdMXfsBLH1WXiRB6e7mDEH2qs=
APP_DEBUG=true
+APP_URL=http://localhost:8080

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

+DB_CONNECTION=mysql
+DB_HOST=127.0.0.1
+DB_PORT=3306
+DB_DATABASE=my_recipes_api
+DB_USERNAME=root
+DB_PASSWORD=root

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

8. Lakukan migrasi database dengan command:

```
php artisan migrate:fresh -seed
```

9. Jalankan aplikasi dengan command:

```
php artisan serve
```

10. Buka Postman dan akses http://localhost:8000/api/recipes

11. Jika anda menggunakan Laragon, ganti url pada .env menjadi:

```diff
+APP_URL=http://my-recipes-api.test
```

Ikuti sampai step ke 10 dengan URL yang anda gunakan. (Step 9 tidak perlu dilakukan jika anda menggunakan Laragon)

12. Setelah step 10 dilakukan maka akan muncul pesan:

```
{
	message: "Unauthenticated."
}
```

Itu berati API sudah berhasil diakses tinggal kita mendapatkan token dengan melakukan Login.
Pastikan kita sudah melakukan register ketika akan melakukan Login.

## API Endpoints Documentation

Berikut dokumentasi API yang tersedia:

-   **[Dokumentasi Postman](https://documenter.getpostman.com/view/13030419/UVkpMuZv)**
