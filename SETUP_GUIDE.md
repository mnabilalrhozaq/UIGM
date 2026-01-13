# Panduan Setup Project UIGM POLBAN

## Langkah-langkah Setup di Laptop Baru

### 1. Clone Repository
```bash
git clone [URL_REPOSITORY]
cd [NAMA_FOLDER]
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Konfigurasi Database

#### a. Buat Database Baru
- Buka phpMyAdmin atau MySQL client
- Buat database baru dengan nama: `uigm_polban` atau `eksperimen`

#### b. Import Database
- Import file SQL: `uigm_polban (3).sql`
- Atau jalankan via command line:
```bash
mysql -u root -p uigm_polban < "uigm_polban (3).sql"
```

### 4. Konfigurasi File .env

Copy file `.env.example` menjadi `.env`:
```bash
copy .env.example .env
```

Edit file `.env` dan sesuaikan:
```
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = uigm_polban
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 5. Set Permissions (jika di Linux/Mac)
```bash
chmod -R 777 writable/
```

### 6. Jalankan Development Server
```bash
php spark serve
```

Atau jika menggunakan Laragon/XAMPP, akses via:
```
http://localhost/[nama-folder]/public
```

### 7. Login ke Sistem

**Akun Default:**

| Username | Password | Role |
|----------|----------|------|
| admin | admin12345678 | Admin Pusat |
| superadmin | superadmin123 | Super Admin |
| user1 | user12345 | User Unit |

**PENTING:** Password di database sudah di-hash. Gunakan password di atas untuk login.

## Troubleshooting

### Tidak Bisa Login
1. **Cek database sudah di-import**
2. **Cek konfigurasi .env sudah benar**
3. **Clear browser cache** (Ctrl+Shift+Delete)
4. **Cek file log** di `writable/logs/` untuk error detail

### Error 404
1. Pastikan akses via folder `public/` atau gunakan `php spark serve`
2. Cek file `.htaccess` ada di folder `public/`

### Error Database Connection
1. Pastikan MySQL/MariaDB sudah running
2. Cek username dan password database di `.env`
3. Pastikan database sudah dibuat dan di-import

### Password Tidak Cocok
Password di database sudah di-hash dengan bcrypt. Jangan ubah langsung di database.
Gunakan password yang tertera di tabel akun default di atas.

## Fitur Utama

### Role User
- Input data sampah
- Lihat dashboard statistik
- Export data (jika diaktifkan)

### Role Pengelola TPS
- Input data sampah TPS
- Monitoring data TPS
- Export data

### Role Admin Pusat
- Review & approve data sampah
- Manajemen harga sampah
- User management
- Laporan & monitoring
- Feature toggle

## Kontak Support
Jika ada masalah, hubungi developer atau cek dokumentasi di folder `docs/`
