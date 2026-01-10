# 🔧 Troubleshooting: Data Tidak Muncul di Review

## ❌ Masalah: Data Waste Tidak Muncul di Admin Pusat

### 🔍 Penyebab Umum

1. **Salah Halaman Review**
   - Data **Waste** harus dilihat di: `/admin-pusat/waste`
   - Data **UIGM** harus dilihat di: `/admin-pusat/review`

2. **Status Data Belum "Dikirim"**
   - Pastikan status data = "DIKIRIM" bukan "DRAFT"

3. **Notifikasi Tidak Aktif**
   - Tabel notifications belum dibuat
   - User admin_pusat tidak ada

## ✅ Solusi Step by Step

### 1. Cek Status Data
```sql
-- Check data waste yang dikirim
SELECT * FROM waste_management WHERE status = 'dikirim';

-- Check data UIGM yang dikirim  
SELECT * FROM penilaian_unit WHERE status = 'dikirim';
```

### 2. Cek di Halaman yang Benar

#### Untuk Data Waste:
- **User kirim data waste** → **Admin buka**: `/admin-pusat/waste`
- Filter status: "Dikirim" atau "Semua"

#### Untuk Data UIGM:
- **User kirim data UIGM** → **Admin buka**: `/admin-pusat/review`
- Filter tipe: "Data UIGM" atau "Semua Data"

### 3. Cek Notifikasi
```sql
-- Run test notifikasi
SOURCE database/test_notification.sql;
```

### 4. Debug Data
```sql
-- Run debug script
SOURCE database/debug_review_data.sql;
```

## 🎯 Workflow yang Benar

### Data Waste:
```
User Input Waste → Status "DIKIRIM" → Admin Pusat buka "Waste Management" → Review & Approve
```

### Data UIGM:
```
User Input UIGM → Status "DIKIRIM" → Admin Pusat buka "Antrian Review" → Review & Approve
```

## 🔧 Quick Fix

### Jika Data Waste Tidak Muncul:
1. Login sebagai Admin Pusat
2. Buka menu **"Waste Management"** (bukan "Antrian Review")
3. Filter status: **"Dikirim"**
4. Data waste akan muncul di sana

### Jika Notifikasi Tidak Muncul:
1. Import tabel notifications:
   ```sql
   SOURCE database/sql/patches/001_add_notifications.sql;
   ```
2. Test notifikasi:
   ```sql
   SOURCE database/test_notification.sql;
   ```

## 📋 Checklist Troubleshooting

- [ ] Data sudah status "DIKIRIM"?
- [ ] Admin buka halaman yang benar?
- [ ] Tabel notifications sudah ada?
- [ ] User admin_pusat sudah ada?
- [ ] Filter status sudah benar?

## 💡 Tips

1. **Data Waste** → Halaman **"Waste Management"**
2. **Data UIGM** → Halaman **"Antrian Review"**
3. **Notifikasi** → Check di dashboard admin pusat
4. **Debug** → Gunakan script SQL yang disediakan

---

**🎯 Remember:** Waste data dan UIGM data direview di halaman yang berbeda!