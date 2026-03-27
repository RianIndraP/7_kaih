# 🚀 Monitoring Kebiasaan Harian - SMK N 5 Telkom Banda Aceh

Aplikasi berbasis web menggunakan Laravel 12 untuk memantau aktivitas dan kebiasaan harian siswa.

---

## 🛠️ Prasyarat (Prerequisites)

Sebelum menjalankan proyek ini, pastikan Anda sudah menginstal:

-   **PHP >= 8.3**
-   **Composer**
-   **Node.js & NPM**
-   **MySQL / MariaDB**

---

## 📥 Langkah Instalasi (Local Development)

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer Anda:

### 1. **Clone Repositori**

    ```bash
    git clone https://github.com/RianIndraP/7_kaih.git
    cd nama-repo
    ```

### 2. **Install Dependensi PHP**

    ```bash
    composer install
    ```

### 3. **Instal Dependensi Frontend**

    ```bash
    npm install
    ```

### 4. **Konfigurasi Environment**

Salin file `.env.example` menjadi `.env`:

    ```bash
    cp .env.example .env
    ```

Buka file `.env` dan sesuaikan pengaturan database Anda (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

### 5. **Generate App Key**

    ```bash
    php artisan key:generate
    ```

---

## 🗄️ Konfigurasi Database (Manual SQL)

Proyek ini menggunakan metode impor SQL manual (bukan Laravel Migrations).

1. Buat database baru di phpMyAdmin dengan nama 7_kaih.
2. Cari file SQL di folder `/database/sql/7_kaih.sql`
3. **Impor** file tersebut ke dalam database `7_kaih` yang baru di buat.

---

## 🌐 Menjalankan Aplikasi (Mode Publik / Ngrok)

Jika Anda ingin membagikan akses localhost agar bisa dibuka di perangkat lain (HP/Laptop teman), gunakan langkah berikut:

### 1. **Jalankan Server Laravel**

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### 2. **Jalankan Ngrok Tunnel** (Buka terminal baru)

```bash
npx ngrok http 8000
```

### 3. **Update .env**

Salin URL HTTPS dari Ngrok (misal: `https://ngrok-free.app`) dan tempel di file `.env`:

```env
APP_URL=https://ngrok-free.app
```

### 4. **Bersihkan Cache**

```bash
php artisan config:clear
```

### 5. **Akses Aplikasi**

Buka URL Ngrok tersebut di browser. Jika muncul halaman "Visit Site", klik tombol tersebut untuk masuk.

---

## 📝 Lisensi

Proyek ini dikembangkan untuk lingkungan internal SMK N 5 Telkom Banda Aceh.

---

### Tips Tambahan:

-   **Folder Database**: Jangan lupa buat folder bernama `database/sql` di dalam proyek Anda dan masukkan file `.sql` Anda ke sana sebelum di-`push` ke GitHub.
-   **Git Ignore**: Pastikan file `.env` Anda tetap ada di dalam file `.gitignore` agar informasi pribadi (seperti password database) tidak tersebar ke GitHub.
