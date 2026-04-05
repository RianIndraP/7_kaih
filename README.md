g# 🚀 Monitoring Kebiasaan Harian

### SMK N 5 Telkom Banda Aceh

Aplikasi berbasis web menggunakan **Laravel 12** untuk memantau aktivitas dan kebiasaan harian siswa.

---

## 🛠️ Prasyarat (Prerequisites)

Pastikan sudah menginstal:

* PHP >= 8.3
* Composer
* Node.js & NPM
* MySQL / MariaDB

---

## 📥 Instalasi (Setelah Clone Repository)

> ⚠️ **Catatan:** Jika Anda mendapatkan project ini dari `git clone`, maka **tidak perlu menjalankan ****`git init`**** atau ****`git remote`**** lagi**.

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

## 🗄️ Setup Database (Manual SQL)

Project ini **tidak menggunakan migration**, gunakan file SQL manual:

1. Buat database baru:
   `7_kaih`

2. Import file:

```
/database/sql/7_kaih.sql
```

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

* File `.env` **tidak boleh di-upload ke GitHub**
* Pastikan `.env` sudah ada di `.gitignore`
* Jangan upload:

  * `/vendor`
  * `/node_modules`

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