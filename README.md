# Laravel Vue Base App - Roles & Permissions

Base app Laravel 12 + Vue 3 + Inertia untuk membangun panel admin dengan autentikasi, manajemen user, role, permission, profil, dan setting aplikasi.

## Deskripsi

Project ini sudah dirapikan sebagai starter kit admin yang ringan dan siap dikembangkan. Fokus utama aplikasi saat ini adalah:

-   autentikasi login dan logout
-   dashboard admin
-   CRUD user
-   CRUD role
-   CRUD permission
-   halaman profil user login
-   setting aplikasi singleton

Codebase lama yang berhubungan dengan exam, student, editor TinyMCE, import/export lama, dan library yang tidak dipakai sudah dibersihkan.

## Stack yang Digunakan

### Backend

-   PHP 8.2+
-   Laravel 12
-   Laravel Fortify
-   Inertia Laravel
-   Spatie Laravel Permission

### Frontend

-   Vue 3
-   Inertia Vue 3
-   Vite
-   Tailwind CSS v4
-   SweetAlert2
-   Font Awesome

### Development Tools

-   Composer
-   NPM
-   Laravel Pint
-   PHPUnit
-   Laravel Pail

## Fitur Utama

### 1. Authentication

-   login dengan email dan password
-   logout
-   redirect otomatis berdasarkan akses user

### 2. Dashboard Admin

Menampilkan ringkasan data utama aplikasi:

-   total user
-   total role
-   total permission
-   total setting aplikasi

### 3. User Management

Fitur modul user:

-   daftar user
-   pencarian user
-   tambah user
-   edit user
-   hapus user
-   assign role ke user

### 4. Role Management

Fitur modul role:

-   daftar role
-   tambah role
-   edit role
-   hapus role
-   assign permission ke role

Role `admin` dipertahankan sebagai role sistem utama.

### 5. Permission Management

Fitur modul permission:

-   daftar permission
-   tambah permission
-   edit permission
-   hapus permission

### 6. Profile Page

Menampilkan data user yang sedang login:

-   nama
-   email
-   role utama
-   daftar role
-   daftar permission
-   status verifikasi email
-   tanggal dibuat
-   tanggal diperbarui

### 7. App Setting

Setting aplikasi bersifat singleton dan berisi:

-   `name_app`
-   `slug`
-   `image`

Digunakan untuk:

-   nama aplikasi di navbar
-   title/sidebar branding
-   title browser
-   favicon/logo aplikasi

File gambar disimpan di `storage/app/public/settings` dan diakses melalui `public/storage`.

## Permission Default Seeder

Permission bawaan saat seeding:

-   `dashboard.view`
-   `settings.index`
-   `users.view`
-   `users.create`
-   `users.edit`
-   `users.delete`
-   `roles.view`
-   `roles.create`
-   `roles.edit`
-   `roles.delete`
-   `permissions.view`
-   `permissions.create`
-   `permissions.edit`
-   `permissions.delete`

## Struktur Folder Penting

```text
app/
  Http/
    Controllers/
      Admin/
      Auth/
    Middleware/
  Models/
  Providers/
  Support/

database/
  migrations/
  seeders/

resources/
  css/
  js/
    Components/
    Layouts/
    Pages/
  views/

routes/
  web.php
```

## Instalasi

### 1. Clone repository

```bash
git clone <repository-url>
cd ujian-online
```

### 2. Install dependency backend

```bash
composer install
```

### 3. Install dependency frontend

```bash
npm install
```

### 4. Siapkan environment

Windows:

```bash
copy .env.example .env
```

Linux/macOS:

```bash
cp .env.example .env
```

Lalu sesuaikan `.env`, minimal:

```env
APP_NAME="Laravel Vue Base App"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate key aplikasi

```bash
php artisan key:generate
```

### 6. Jalankan migration dan seeder

```bash
php artisan migrate --seed
```

### 7. Buat storage link

```bash
php artisan storage:link
```

## Menjalankan Project

### Opsi 1 - Jalankan terpisah

Backend:

```bash
php artisan serve
```

Frontend:

```bash
npm run dev
```

### Opsi 2 - Jalankan mode development lengkap

```bash
composer run dev
```

Perintah ini akan menjalankan:

-   web server Laravel
-   queue listener
-   log watcher
-   Vite dev server

## Akun Default Seeder

-   email: `admin@gmail.com`
-   password: `password`

## Route Utama

### Auth

-   `/login`
-   `/logout`

### Admin

-   `/admin/dashboard`
-   `/admin/profile`
-   `/admin/settings`
-   `/admin/users`
-   `/admin/roles`
-   `/admin/permissions`
