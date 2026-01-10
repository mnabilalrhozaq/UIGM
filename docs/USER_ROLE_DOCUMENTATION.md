# 📋 DOKUMENTASI ROLE USER - WASTE MANAGEMENT UIGM

## 🎯 Overview

Fitur **Role User** memungkinkan pengguna (mahasiswa, dosen, staff) untuk menginput dan mengelola data sampah (waste management) mereka sendiri melalui interface yang user-friendly dengan sidebar kategori dan form input yang intuitif.

---

## 🏗️ Arsitektur Sistem

### 📁 **Struktur File**
```
📁 Role User Files/
├── 📄 app/Controllers/User/Kriteria.php     # Controller utama
├── 📄 app/Models/KriteriaModel.php          # Model data kriteria
├── 📄 app/Views/user/kriteria.php           # View halaman user
├── 📄 app/Database/Migrations/
│   ├── 2026-01-02-100000_CreateKriteriaUigmTable.php
│   └── 2026-01-02-110000_AddUserFields.php
├── 📄 app/Database/Seeds/
│   ├── UserSeeder.php                       # Seeder user
│   └── KriteriaSeeder.php                   # Seeder sample data
└── 📄 scripts/setup_user_role.php           # Setup script
```

### 🗄️ **Database Schema**

#### Tabel: `kriteria_uigm`
```sql
CREATE TABLE kriteria_uigm (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    tanggal DATE NOT NULL,
    unit_prodi VARCHAR(100) NOT NULL,
    jenis_sampah ENUM('Kertas','Plastik','Organik','Anorganik','Limbah Cair','B3') NOT NULL,
    satuan VARCHAR(10) NOT NULL,
    jumlah DECIMAL(10,2) NOT NULL,
    gedung VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_user_id (user_id),
    INDEX idx_user_tanggal (user_id, tanggal),
    INDEX idx_jenis_sampah (jenis_sampah),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);
```

#### Update Tabel: `users`
```sql
-- Tambahan field untuk user
ALTER TABLE users ADD COLUMN full_name VARCHAR(255) NULL AFTER nama_lengkap;
ALTER TABLE users ADD COLUMN unit_prodi VARCHAR(100) NULL AFTER full_name;
ALTER TABLE users MODIFY COLUMN role ENUM('admin_pusat','admin_unit','super_admin','user') NOT NULL;
```

---

## 🔐 Hak Akses & Keamanan

### 👤 **Role User Permissions**
- ✅ **Create**: Input data sampah baru
- ✅ **Read**: Lihat data sampah milik sendiri
- ❌ **Read Others**: Tidak bisa lihat data user lain
- ✅ **Delete**: Hapus data sampah milik sendiri
- ❌ **Delete Others**: Tidak bisa hapus data user lain
- ❌ **Admin Functions**: Tidak ada akses ke fitur admin

### 🛡️ **Security Features**
- **CSRF Protection**: Semua form dilindungi CSRF token
- **Input Validation**: Server-side validation untuk semua input
- **Data Ownership**: User hanya bisa akses data miliknya
- **SQL Injection Prevention**: Menggunakan Query Builder CI4
- **XSS Protection**: Auto-escaping pada output

---

## 🎨 User Interface

### 📱 **Layout Responsif**
- **Sidebar Kiri**: Kategori sampah dengan counter
- **Konten Kanan**: Form input + tabel data
- **Mobile Friendly**: Responsive design untuk semua device
- **Modern UI**: Bootstrap 5 + custom styling

### 🎯 **Sidebar Kategori**
```
📂 Kategori Sampah
├── 📋 Semua (15)
├── 📄 Kertas (5)
├── 🍶 Plastik (3)
├── 🌿 Organik (4)
├── 🔩 Anorganik (2)
├── 💧 Limbah Cair (1)
└── ⚠️ B3 (0)
```

### 📝 **Form Input**
```
┌─────────────────────────────────────┐
│ 📅 Tanggal        📍 Unit/Prodi     │
│ 🗑️ Jenis Sampah   ⚖️ Satuan (Auto)  │
│ 🔢 Jumlah         🏢 Gedung         │
│ [💾 Simpan] [🔄 Batal]             │
└─────────────────────────────────────┘
```

---

## ⚙️ Fitur Utama

### 1. **Auto-Fill Satuan**
```javascript
Jenis Sampah → Satuan (Otomatis)
├── Kertas → kg
├── Plastik → kg
├── Organik → kg
├── Anorganik → kg
├── Limbah Cair → L
└── B3 → kg
```

### 2. **Filter Kategori**
- **URL Parameter**: `?jenis=Plastik`
- **Real-time Filtering**: Sidebar update otomatis
- **Counter Update**: Jumlah data per kategori

### 3. **Data Management**
- **CRUD Operations**: Create, Read, Delete
- **Data Validation**: Client & server-side
- **Error Handling**: User-friendly error messages
- **Success Feedback**: Flash messages

---

## 🚀 Installation & Setup

### 1. **Jalankan Setup Script**
```bash
php scripts/setup_user_role.php
```

### 2. **Manual Setup (Alternatif)**
```bash
# 1. Jalankan Migration
php spark migrate

# 2. Jalankan Seeder
php spark db:seed UserSeeder
php spark db:seed KriteriaSeeder

# 3. Start Server
php spark serve --host=localhost --port=8080
```

### 3. **Verifikasi Setup**
```bash
# Cek tabel
mysql -u root -p uigm_polban -e "SHOW TABLES LIKE 'kriteria_uigm';"

# Cek user
mysql -u root -p uigm_polban -e "SELECT username, role FROM users WHERE role='user';"
```

---

## 👥 Akun Testing

### 🔑 **User Accounts**
```yaml
User 1:
  Username: user1
  Password: password123
  Email: user1@polban.ac.id
  Unit: Teknik Informatika

User 2:
  Username: user2
  Password: password123
  Email: user2@polban.ac.id
  Unit: Teknik Elektro

Mahasiswa:
  Username: mahasiswa1
  Password: password123
  Email: mahasiswa1@polban.ac.id
  Unit: Teknik Mesin

Dosen:
  Username: dosen1
  Password: password123
  Email: dosen1@polban.ac.id
  Unit: Teknik Kimia
```

---

## 🔗 API Endpoints

### 📍 **Routes**
```php
// User Routes (Protected - Role: User)
$routes->group('user', ['filter' => 'auth'], function ($routes) {
    $routes->get('kriteria', 'User\Kriteria::index');           // Halaman utama
    $routes->post('kriteria', 'User\Kriteria::save');           // Simpan data
    $routes->get('kriteria/delete/(:num)', 'User\Kriteria::delete/$1'); // Hapus data
});
```

### 📊 **Controller Methods**
```php
class Kriteria extends BaseController
{
    public function index(): string                    // Tampil halaman + data
    public function save(): ResponseInterface          // Simpan data baru
    public function delete($id): ResponseInterface     // Hapus data (dengan validasi ownership)
}
```

---

## 🧪 Testing Guide

### 1. **Login Testing**
```
URL: http://localhost:8080/auth/login
Username: user1
Password: password123
Expected: Redirect ke dashboard atau halaman user
```

### 2. **Access Control Testing**
```
# Test 1: Akses tanpa login
URL: http://localhost:8080/user/kriteria
Expected: Redirect ke login

# Test 2: Akses dengan role admin
Login sebagai adminpusat → akses /user/kriteria
Expected: Access denied atau redirect

# Test 3: Akses dengan role user
Login sebagai user1 → akses /user/kriteria
Expected: Halaman kriteria tampil
```

### 3. **Data Ownership Testing**
```
# Test 1: User1 coba hapus data User2
Login sebagai user1
Coba akses: /user/kriteria/delete/{id_data_user2}
Expected: Error "Data tidak ditemukan atau bukan milik Anda"

# Test 2: User hanya lihat data sendiri
Login sebagai user1
Expected: Hanya tampil data dengan user_id = user1.id
```

### 4. **Form Validation Testing**
```
# Test 1: Input kosong
Submit form tanpa isi
Expected: Error validation messages

# Test 2: Jumlah negatif
Input jumlah: -5
Expected: Error "Jumlah harus lebih dari 0"

# Test 3: Tanggal invalid
Input tanggal: "invalid-date"
Expected: Error "Format tanggal tidak valid"
```

---

## 🐛 Troubleshooting

### ❌ **Common Issues**

#### 1. **Migration Error**
```bash
Error: Table 'kriteria_uigm' already exists
Solution: 
php spark migrate:rollback
php spark migrate
```

#### 2. **Foreign Key Error**
```bash
Error: Cannot add foreign key constraint
Solution: Pastikan tabel 'users' sudah ada dan memiliki kolom 'id'
```

#### 3. **Access Denied**
```bash
Error: Akses ditolak
Solution: 
1. Pastikan user sudah login
2. Cek role user = 'user'
3. Cek session data
```

#### 4. **Data Tidak Tampil**
```bash
Issue: Tabel kosong meskipun ada data
Solution:
1. Cek user_id di session
2. Cek data di database: SELECT * FROM kriteria_uigm WHERE user_id = X
3. Cek filter kategori
```

### 🔧 **Debug Commands**
```bash
# Cek session
php spark routes | grep user

# Cek database
mysql -u root -p uigm_polban -e "SELECT * FROM kriteria_uigm LIMIT 5;"

# Cek logs
tail -f writable/logs/log-*.php
```

---

## 📈 Performance & Optimization

### ⚡ **Database Optimization**
```sql
-- Index untuk performa
CREATE INDEX idx_user_tanggal ON kriteria_uigm(user_id, tanggal);
CREATE INDEX idx_jenis_sampah ON kriteria_uigm(jenis_sampah);
CREATE INDEX idx_created_at ON kriteria_uigm(created_at);
```

### 🚀 **Frontend Optimization**
- **Lazy Loading**: Tabel data dengan pagination
- **AJAX Filtering**: Filter kategori tanpa reload
- **Caching**: Browser caching untuk assets
- **Minification**: CSS/JS minified

### 📊 **Monitoring**
```php
// Log user activities
log_message('info', "User {$userId} added waste data: {$jenisSampah}");

// Performance monitoring
$start = microtime(true);
// ... operations ...
$duration = microtime(true) - $start;
log_message('debug', "Operation took {$duration} seconds");
```

---

## 🔮 Future Enhancements

### 🌟 **Planned Features**
- [ ] **Bulk Import**: Upload CSV untuk multiple data
- [ ] **Data Export**: Export data user ke Excel/PDF
- [ ] **Charts & Analytics**: Visualisasi data sampah user
- [ ] **Notifications**: Reminder untuk input data
- [ ] **Mobile App**: Native mobile application
- [ ] **Photo Upload**: Upload foto sampah
- [ ] **QR Code**: Scan QR untuk quick input
- [ ] **Gamification**: Points & badges system

### 🔧 **Technical Improvements**
- [ ] **API v2**: RESTful API dengan authentication
- [ ] **Real-time Updates**: WebSocket untuk live updates
- [ ] **Caching Layer**: Redis untuk performance
- [ ] **Search Function**: Advanced search & filter
- [ ] **Audit Trail**: Log semua perubahan data
- [ ] **Data Validation**: Advanced business rules
- [ ] **Multi-language**: Internationalization support

---

## 📞 Support & Contact

### 🆘 **Getting Help**
- **Documentation**: Baca file ini dan README.md
- **Issues**: Create GitHub issue untuk bug reports
- **Email**: dev@polban.ac.id untuk pertanyaan development
- **Phone**: +62-22-1234567 untuk support urgent

### 🕐 **Support Hours**
- **Weekdays**: 08:00 - 17:00 WIB
- **Weekend**: Emergency only
- **Response Time**: < 24 hours untuk issues

---

## 📄 License & Credits

### 📜 **License**
MIT License - See [LICENSE](../LICENSE) file for details.

### 🙏 **Credits**
- **Development Team**: POLBAN IT Department
- **Framework**: CodeIgniter 4.6.4
- **UI Framework**: Bootstrap 5.3.2
- **Icons**: Font Awesome 6.4.0
- **Testing**: PHPUnit & Manual Testing

---

**🎉 FITUR ROLE USER SIAP DIGUNAKAN!**

*Dokumentasi ini akan terus diupdate seiring dengan pengembangan fitur.*