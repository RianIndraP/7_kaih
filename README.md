# 🚀 Monitoring Kebiasaan Harian

### SMK N 5 Telkom Banda Aceh

Aplikasi berbasis web menggunakan **Laravel 12** untuk memantau aktivitas dan kebiasaan harian siswa.

---

## 🛠️ Prasyarat (Prerequisites)

Pastikan sudah menginstal:

-   PHP >= 8.4
-   Composer
-   Node.js & NPM
-   MySQL / MariaDB

---

## 📥 Instalasi (Setelah Clone Repository)

> ⚠️ **Catatan:** Jika Anda mendapatkan project ini dari `git clone`, maka **tidak perlu menjalankan \*\***`git init`\***\* atau \*\***`git remote`\***\* lagi**.

### 1. Clone Repository

```bash
git clone https://github.com/RianIndraP/7_kaih.git
cd 7_kaih
```

---

### 2. Install Dependensi Backend

```bash
composer install
```

---

### 3. Install Dependensi Frontend

```bash
npm install
```

---

### 4. Jalankan Vite (WAJIB ⚠️)

> ❗ Tanpa ini, Tailwind CSS & JavaScript tidak akan berjalan.

```bash
npm run dev
```

📌 Biarkan terminal ini tetap berjalan selama development.

---

### 5. Konfigurasi Environment

```bash
cp .env.example .env
```

Edit bagian database di `.env`:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_DATABASE=7_kaih
DB_USERNAME=root
DB_PASSWORD=
```

---

### 6. Generate App Key

```bash
php artisan key:generate
```

---

## 🔔 Setup Firebase (WAJIB)

Project ini menggunakan **Firebase Cloud Messaging (FCM)** untuk notifikasi.

### 1. Tambahkan di `.env`

```env
FIREBASE_API_KEY=your_api_key
FIREBASE_AUTH_DOMAIN=your_project.firebaseapp.com
FIREBASE_PROJECT_ID=your_project_id
FIREBASE_STORAGE_BUCKET=your_project.appspot.com
FIREBASE_MESSAGING_SENDER_ID=your_sender_id
FIREBASE_APP_ID=your_app_id
```

### 2. Setup Service Account (Backend)

1. Buka Firebase Console
2. Masuk ke:  
   **Project Settings → Service Accounts**
3. Download file JSON

4. Simpan di:

storage/app/firebase.json

> ⚠️ Jangan upload file ini ke GitHub (sangat sensitif)

---

Jika tidak memiliki akses ke Firebase project, silakan hubungi [Admin Project](https://wa.me/6287817956703) untuk mendapatkan file konfigurasi.

## 🗄️ Setup Database (Manual SQL)

Project ini **tidak menggunakan migration**, gunakan file SQL manual:

### 1. Buat database baru:
   `7_kaih`

### 2. Import file:

```
/database/sql/7_kaih.sql
```

> 📌 Ini berisi struktur awal + data penting

### 3. Jalankan Migration

```bash
php artisan migrate
```

> 📌 Digunakan untuk menambahkan/update tabel terbaru dari Laravel

---

## ▶️ Menjalankan Aplikasi

Jalankan Laravel:

```bash
php artisan serve
```

Akses di browser:

```
http://localhost:8000
```

---

## ⏰ Menjalankan Scheduler (WAJIB untuk Notifikasi)

Scheduler digunakan untuk menjalankan tugas otomatis seperti **notifikasi harian**.

```bash
php artisan schedule:work
```

> 📌 Biarkan terminal ini tetap berjalan selama development.

| ⚠️ PENTING |
| :--- |
| **Jika scheduler tidak dijalankan:** <br> ❌ Notifikasi tidak akan pernah terkirim |

---

## 🌐 Mode Publik (Ngrok)

Jika ingin diakses dari HP / perangkat lain:

### 1. Jalankan Laravel

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### 2. Jalankan Ngrok

```bash
npx ngrok http 8000
```

### 3. Update `.env`

```env
APP_URL=https://xxxx.ngrok-free.app
```

### 4. Clear Cache

```bash
php artisan config:clear
```

---

## 🔄 Workflow Development

Gunakan **2 terminal**:

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Terminal 3:

```bash
php artisan schedule:work
```

---

## ☁️ Workflow GitHub

### 1. Lakukan Perubahan pada Project

Edit atau tambahkan file sesuai kebutuhan.

---

### 2. Cek Perubahan

```bash
git status
```

---

### 3. Tambahkan Perubahan

```bash
git add .
```

---

### 4. Commit Perubahan

```bash
git commit -m "pesan perubahan"
```

---

### 5. Ambil Update Terbaru (Disarankan)

```bash
git pull origin main
```

---

### 6. Push ke GitHub

```bash
git push origin main
```

---

## ⚠️ Important Notes

-   File `.env` **tidak boleh di-upload ke GitHub**
-   Pastikan `.env` sudah ada di `.gitignore`
-   Jangan upload:

    -   `/vendor`
    -   `/node_modules`

---

## 💡 Troubleshooting

**CSS tidak muncul / berantakan:**

```bash
npm run dev
```

**Error config / env:**

```bash
php artisan config:clear
```

---

## 📝 Lisensi

Digunakan untuk lingkungan internal
**SMK N 5 Telkom Banda Aceh**

---
