# UI GreenMetric POLBAN - Waste Management System

Sistem Manajemen Sampah untuk UI GreenMetric Politeknik Negeri Bandung

## 📋 Deskripsi

Aplikasi web untuk mengelola data sampah di lingkungan Politeknik Negeri Bandung sebagai bagian dari program UI GreenMetric. Sistem ini memungkinkan berbagai unit untuk mencatat, melaporkan, dan memantau pengelolaan sampah secara digital.

## 🚀 Fitur Utama

### Admin Pusat
- Dashboard monitoring seluruh unit
- Manajemen harga sampah
- Review dan approval data sampah dari unit
- Laporan dan statistik komprehensif
- Export data ke PDF/Excel

### Pengelola TPS
- Input data sampah TPS
- Monitoring data sampah unit
- Export laporan TPS

### User (Unit)
- Input data sampah unit
- Tracking status approval
- Melihat informasi harga sampah
- Export laporan unit

## 🛠️ Teknologi

- **Framework**: CodeIgniter 4.6.4
- **PHP**: 7.4 atau lebih tinggi
- **Database**: MySQL 8.0+
- **Frontend**: Bootstrap 5, Font Awesome 6
- **Library**: TCPDF (untuk export PDF)

## 📦 Instalasi

### Prasyarat

- PHP 7.4 atau lebih tinggi dengan ekstensi:
  - intl
  - mbstring
  - json
  - mysqlnd
- MySQL 8.0 atau lebih tinggi
- Composer
- Web server (Apache/Nginx) atau Laragon/XAMPP

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/username/uigm-polban.git
   cd uigm-polban
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Konfigurasi environment**
   ```bash
   cp .env.example .env
   ```
   
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   database.default.hostname = localhost
   database.default.database = eksperimen
   database.default.username = root
   database.default.password = 
   database.default.port = 3306
   ```

4. **Import database**
   
   Import file SQL yang ada di folder `database/`:
   ```bash
   mysql -u root -p eksperimen < database/eksperimen.sql
   ```
   
   Atau gunakan phpMyAdmin untuk import database.

5. **Set permissions**
   
   Pastikan folder `writable` memiliki permission yang tepat:
   ```bash
   chmod -R 777 writable/
   ```

6. **Jalankan aplikasi**
   
   Menggunakan PHP built-in server:
   ```bash
   php spark serve
   ```
   
   Atau akses melalui web server di:
   ```
   http://localhost/eksperimen/public
   ```

## 👥 Default User Accounts

Setelah import database, gunakan akun berikut untuk login:

### Admin Pusat
- **Username**: `admin`
- **Password**: `admin123`

### Pengelola TPS
- **Username**: `tps1`
- **Password**: `tps123`

### User (Unit)
- **Username**: `user1`
- **Password**: `user123`

⚠️ **PENTING**: Ganti password default setelah login pertama kali!

## 📁 Struktur Project

```
eksperimen/
├── app/
│   ├── Controllers/      # Controller untuk routing
│   ├── Models/          # Model untuk database
│   ├── Services/        # Business logic layer
│   ├── Views/           # Template views
│   └── Config/          # Konfigurasi aplikasi
├── public/              # Public assets (CSS, JS, images)
├── writable/            # Cache, logs, uploads
├── database/            # SQL files dan migrations
├── vendor/              # Composer dependencies
└── .env                 # Environment configuration (jangan di-commit!)
```

## 🔧 Konfigurasi

### Database

Struktur database utama:
- `users` - Data pengguna sistem
- `units` - Data unit/fakultas
- `master_harga_sampah` - Master data harga sampah
- `waste_management` - Data transaksi sampah
- `log_perubahan_harga` - Log perubahan harga

### Environment Variables

Konfigurasi penting di `.env`:
- `CI_ENVIRONMENT` - Mode aplikasi (development/production)
- `database.*` - Konfigurasi database
- `app.baseURL` - Base URL aplikasi

## 📝 Penggunaan

### Workflow Sistem

1. **Admin Pusat** mengatur master data harga sampah
2. **User/TPS** menginput data sampah sesuai unit masing-masing
3. Data sampah masuk ke status "dikirim" untuk review
4. **Admin Pusat** melakukan review dan approval
5. Data yang disetujui masuk ke laporan dan statistik

### Fitur Pagination

Sistem menggunakan pagination untuk:
- Daftar harga sampah (5 items per halaman)
- Manajemen harga admin (10 items per halaman dengan filter)
- Data sampah per unit

### Export Data

Tersedia export ke format:
- **PDF** - Untuk laporan formal
- **Excel** - Untuk analisis data

## 🐛 Troubleshooting

### Error: "Table not found"

Pastikan database sudah di-import dengan benar:
```bash
mysql -u root -p eksperimen < database/eksperimen.sql
```

### Error: "Permission denied" pada folder writable

Set permission yang tepat:
```bash
chmod -R 777 writable/
```

### Pagination tidak muncul

Pastikan data lebih dari limit per halaman (5 atau 10 items).

### Error 404 pada routing

Pastikan `.htaccess` ada di folder `public/` dan mod_rewrite Apache aktif.

## 🔐 Security

- Password di-hash menggunakan PHP `password_hash()`
- CSRF protection aktif
- Session management dengan timeout
- Input validation dan sanitization
- SQL injection protection via Query Builder

⚠️ **Untuk Production**:
- Ganti semua password default
- Set `CI_ENVIRONMENT = production` di `.env`
- Aktifkan HTTPS
- Backup database secara berkala
- Monitor log files di `writable/logs/`

## 📊 Database Backup

Backup database secara manual:
```bash
mysqldump -u root -p eksperimen > backup_$(date +%Y%m%d).sql
```

Atau gunakan script backup yang tersedia di folder `scripts/`.

## 🤝 Contributing

Jika ingin berkontribusi:
1. Fork repository
2. Buat branch baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 License

Project ini dibuat untuk keperluan internal Politeknik Negeri Bandung.

## 📞 Kontak

Untuk pertanyaan atau dukungan, hubungi:
- Email: admin@polban.ac.id
- Website: https://www.polban.ac.id

## 📚 Dokumentasi Tambahan

Dokumentasi lengkap tersedia di folder `docs/`:
- Setup Guide
- User Manual
- API Documentation
- Database Schema

## 🔄 Changelog

Lihat file `CHANGELOG.md` untuk riwayat perubahan.

## ✅ Testing

Jalankan test suite:
```bash
composer test
```

## 🙏 Acknowledgments

- CodeIgniter 4 Framework
- Bootstrap 5
- Font Awesome
- TCPDF Library
- Politeknik Negeri Bandung

---

**Dibuat dengan ❤️ untuk UI GreenMetric POLBAN**
