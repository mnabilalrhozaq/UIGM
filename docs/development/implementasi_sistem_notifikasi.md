# Implementasi Sistem Notifikasi Admin Pusat

## Masalah yang Dipecahkan
- Data dari User tidak muncul di antrian review Admin Pusat
- Tidak ada notifikasi real-time ketika User mengirim data
- Admin Pusat tidak tahu ada data baru yang perlu direview

## Solusi yang Diimplementasikan

### 1. Model Notifikasi (`app/Models/NotificationModel.php`)
**Fitur:**
- Tabel `notifications` untuk menyimpan notifikasi
- Method untuk create, read, update notifikasi
- Support untuk berbagai tipe notifikasi (info, success, warning, danger)
- JSON data field untuk menyimpan metadata

**Method Utama:**
```php
// Buat notifikasi untuk data UIGM baru
createDataSubmissionNotification($unitName, $kategori, $indikator, $penilaianId)

// Buat notifikasi untuk data waste baru  
createWasteSubmissionNotification($unitName, $jenisSampah, $wasteId)

// Get unread count dan notifications
getUnreadCount($userId)
getUnreadNotifications($userId)
```

### 2. Update Controller User (`app/Controllers/User/`)

**Pengisian.php:**
- Tambah notifikasi saat data UIGM dikirim (status = 'dikirim')
- Notifikasi untuk single data dan bulk kategori
- Error handling yang comprehensive

**Waste.php:**
- Tambah notifikasi saat data waste dikirim
- Notifikasi untuk insert dan update data
- Integration dengan sistem existing

### 3. API Controller Notifikasi (`app/Controllers/Api/NotificationController.php`)
**Endpoint:**
- `GET /api/notification/unread-count` - Get jumlah notifikasi belum dibaca
- `GET /api/notification/list` - Get daftar notifikasi
- `POST /api/notification/{id}/read` - Mark notifikasi sebagai dibaca
- `POST /api/notification/mark-all-read` - Mark semua sebagai dibaca

### 4. Database Schema (`database_notifications_table.sql`)
```sql
CREATE TABLE notifications (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  type ENUM('info','success','warning','danger') DEFAULT 'info',
  data JSON DEFAULT NULL,
  is_read TINYINT(1) DEFAULT 0,
  read_at DATETIME DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 5. Debug Tools (`app/Controllers/Debug/DataController.php`)
**Untuk troubleshooting:**
- `/debug/check-data` - Lihat semua data dengan status 'dikirim'
- `/debug/create-sample-data` - Buat sample data untuk testing
- View lengkap untuk monitoring data

## Alur Kerja Sistem

### 1. User Mengirim Data UIGM:
```
User mengisi form → Klik "Kirim" → Status berubah ke 'dikirim' 
→ Notifikasi dikirim ke semua Admin Pusat → Admin dapat melihat di review queue
```

### 2. User Mengirim Data Waste:
```
User input waste → Klik "Kirim" → Status berubah ke 'dikirim'
→ Notifikasi dikirim ke Admin Pusat → Admin dapat review di waste management
```

### 3. Admin Pusat Menerima Notifikasi:
```
Notifikasi muncul di dashboard → Admin klik notifikasi → Redirect ke halaman review
→ Admin review dan approve/reject → Status data berubah
```

## Cara Testing

### 1. Setup Database:
```sql
-- Jalankan script SQL
SOURCE database_notifications_table.sql;
```

### 2. Check Data Existing:
```
Buka: http://localhost:8080/debug/check-data
```

### 3. Create Sample Data (jika kosong):
```
Buka: http://localhost:8080/debug/create-sample-data
```

### 4. Test Alur Lengkap:
1. Login sebagai User
2. Isi data UIGM atau Waste
3. Klik "Kirim"
4. Login sebagai Admin Pusat
5. Check dashboard dan review queue
6. Lihat notifikasi baru

## Fitur Notifikasi

### 1. Real-time Notifications:
- Notifikasi langsung saat data dikirim
- Badge counter untuk unread notifications
- Auto-refresh notification list

### 2. Rich Notifications:
- Title dan message yang descriptive
- Metadata JSON untuk action URLs
- Tipe notifikasi (info, success, warning, danger)

### 3. User Experience:
- Click notification → redirect ke halaman terkait
- Mark as read functionality
- Mark all as read
- Notification history

## Troubleshooting

### Jika Data Tidak Muncul di Review Queue:
1. Check `/debug/check-data` untuk melihat data dengan status 'dikirim'
2. Pastikan User sudah klik "Kirim" bukan "Simpan Draft"
3. Check log error di `writable/logs/`

### Jika Notifikasi Tidak Muncul:
1. Pastikan tabel `notifications` sudah dibuat
2. Check API endpoint `/api/notification/unread-count`
3. Pastikan User role = 'admin_pusat'

### Jika Error Database:
1. Check koneksi database
2. Pastikan foreign key constraints benar
3. Check user permissions

## Status Implementasi
✅ **Model Notifikasi** - Lengkap dengan semua method
✅ **Controller Integration** - User controllers updated
✅ **API Endpoints** - REST API untuk notifikasi
✅ **Database Schema** - Tabel dan sample data
✅ **Debug Tools** - Tools untuk troubleshooting
✅ **Error Handling** - Comprehensive error handling
✅ **Documentation** - Dokumentasi lengkap

Sistem notifikasi sekarang aktif dan siap digunakan! 🚀