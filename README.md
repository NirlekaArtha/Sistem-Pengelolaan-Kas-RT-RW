# 🚀 Quick Start

## 📦 Prasyarat

Pastikan beberapa dependency berikut sudah terinstall:

- PHP
- Composer
- NPM / Node.js
- MariaDB

### Arch Linux

```bash
sudo pacman -S php composer npm mariadb
```

---

# 🗄️ Setup Database MariaDB

Login ke MariaDB:

```bash
mariadb -u root -p
```

Lalu buat database baru bernama `laravel`:

```sql
CREATE DATABASE laravel;
EXIT;
```

---

# 📄 Setup Environment

Copy file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Lalu ubah konfigurasi database pada file `.env` Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan akun MariaDB:

```env
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
```


---

# ⚙️ Install Dependency

Jalankan perintah berikut di root project:

```bash
npm install
composer install
```

---

# 🔑 Generate App Key

```bash
php artisan key:generate
```

---

# 🗄️ Migrasi Database & Seeder

Jalankan migrasi database sekaligus seeder:

```bash
php artisan migrate --seed
```

---

# ▶️ Menjalankan Aplikasi

```bash
php artisan serve
```

Aplikasi akan berjalan di:

```txt
http://localhost:8000
```

---

# 🔐 Login Dashboard Admin

Buka halaman berikut:

```txt
http://localhost:8000/dashboard/login
```

Gunakan akun default berikut:

| Role | Email | Password |
|------|------|------|
| Admin | `admin@gmail.com` | `admin123` |

---
