# Testing Guide: Fitur Hapus Jenis Sampah

## ✅ Fitur yang Sudah Ditambahkan

### 1. UI Components
- ✅ Tombol **Edit** (kuning) - untuk edit data
- ✅ Tombol **Toggle Status** (abu/hijau) - untuk aktifkan/nonaktifkan
- ✅ Tombol **Hapus** (merah) - untuk hapus data

### 2. Backend
- ✅ Method `delete()` di `app/Controllers/Admin/Harga.php`
- ✅ Route `POST /admin-pusat/manajemen-harga/delete/(:num)`
- ✅ Validasi penggunaan data di transaksi
- ✅ Logging penghapusan

### 3. JavaScript Functions
- ✅ `deleteHarga()` - handle delete dengan konfirmasi
- ✅ `toggleStatus()` - handle toggle status
- ✅ `editHarga()` - handle edit data

---

## 🧪 Cara Testing

### Test 1: Hapus Data yang Belum Digunakan ✅

**Langkah:**
1. Login sebagai admin pusat
2. Buka menu **Manajemen Sampah**
3. Cari data yang baru ditambahkan (belum ada transaksi)
4. Klik tombol **Hapus** (merah dengan icon trash)
5. Akan muncul dialog konfirmasi:
   ```
   Apakah Anda yakin ingin menghapus jenis sampah "Nama Sampah"?
   
   Perhatian: Data yang sudah digunakan di transaksi tidak akan terhapus.
   ```
6. Klik **OK**

**Expected Result:**
- ✅ Muncul alert sukses: "Jenis sampah berhasil dihapus"
- ✅ Halaman reload otomatis setelah 1.5 detik
- ✅ Data hilang dari tabel
- ✅ Log penghapusan tercatat

---

### Test 2: Hapus Data yang Sudah Digunakan ❌

**Langkah:**
1. Login sebagai admin pusat
2. Buka menu **Manajemen Sampah**
3. Cari data yang sudah digunakan di transaksi (misal: Plastik PET)
4. Klik tombol **Hapus** (merah)
5. Klik **OK** di dialog konfirmasi

**Expected Result:**
- ❌ Muncul alert error: 
  ```
  Tidak dapat menghapus jenis sampah 'Plastik PET' karena sudah 
  digunakan dalam 15 transaksi. Anda dapat menonaktifkan jenis sampah ini.
  ```
- ✅ Data TIDAK terhapus (tetap ada di tabel)
- ✅ Sistem aman dari data loss

---

### Test 3: Hapus Data Kosong (Bug Fix) 🐛

**Langkah:**
1. Login sebagai admin pusat
2. Buka menu **Manajemen Sampah**
3. Cari data dengan kolom **Jenis Sampah** kosong atau "-"
4. Klik tombol **Hapus** (merah)
5. Klik **OK** di dialog konfirmasi

**Expected Result:**
- ✅ Muncul alert sukses: "Jenis sampah berhasil dihapus"
- ✅ Data kosong terhapus
- ✅ Problem solved! 🎉

---

### Test 4: Toggle Status (Nonaktifkan) 🔄

**Langkah:**
1. Login sebagai admin pusat
2. Buka menu **Manajemen Sampah**
3. Cari data yang statusnya **Aktif**
4. Klik tombol **Toggle Status** (abu-abu dengan icon eye-slash)
5. Klik **OK** di dialog konfirmasi

**Expected Result:**
- ✅ Muncul alert sukses: "Status berhasil diubah"
- ✅ Status berubah menjadi **Nonaktif**
- ✅ Data tidak muncul di dropdown user/TPS
- ✅ Data lama tetap aman

---

### Test 5: Toggle Status (Aktifkan) 🔄

**Langkah:**
1. Login sebagai admin pusat
2. Buka menu **Manajemen Sampah**
3. Cari data yang statusnya **Nonaktif**
4. Klik tombol **Toggle Status** (hijau dengan icon eye)
5. Klik **OK** di dialog konfirmasi

**Expected Result:**
- ✅ Muncul alert sukses: "Status berhasil diubah"
- ✅ Status berubah menjadi **Aktif**
- ✅ Data muncul kembali di dropdown user/TPS

---

### Test 6: Edit Data 📝

**Langkah:**
1. Login sebagai admin pusat
2. Buka menu **Manajemen Sampah**
3. Klik tombol **Edit** (kuning)
4. Ubah data (misal: harga, satuan, deskripsi)
5. Klik **Simpan**

**Expected Result:**
- ✅ Muncul alert sukses: "Data berhasil diperbarui"
- ✅ Data terupdate di tabel
- ✅ Log perubahan tercatat

---

### Test 7: Konfirmasi Dialog - Cancel ❌

**Langkah:**
1. Klik tombol **Hapus**
2. Di dialog konfirmasi, klik **Cancel** atau **X**

**Expected Result:**
- ✅ Dialog tertutup
- ✅ Data TIDAK terhapus
- ✅ Tidak ada perubahan

---

### Test 8: Error Handling - Data Tidak Ditemukan 🔍

**Langkah:**
1. Buka browser console (F12)
2. Jalankan manual:
   ```javascript
   deleteHarga(99999, 'Test')
   ```

**Expected Result:**
- ❌ Muncul alert error: "Data tidak ditemukan"
- ✅ Tidak ada crash
- ✅ Error handling bekerja

---

### Test 9: Error Handling - Session Invalid 🔒

**Langkah:**
1. Logout dari sistem
2. Buka URL langsung: `/admin-pusat/manajemen-harga/delete/1`

**Expected Result:**
- ❌ Redirect ke login atau error: "Session invalid"
- ✅ Keamanan terjaga

---

## 📋 Checklist Testing

### UI Testing
- [ ] Tombol Edit muncul (kuning)
- [ ] Tombol Toggle Status muncul (abu/hijau)
- [ ] Tombol Hapus muncul (merah)
- [ ] Icon tombol sesuai (edit, eye, trash)
- [ ] Tombol responsive di mobile

### Functional Testing
- [ ] Delete data baru berhasil
- [ ] Delete data lama ditolak dengan pesan error
- [ ] Delete data kosong berhasil
- [ ] Toggle status aktif → nonaktif
- [ ] Toggle status nonaktif → aktif
- [ ] Edit data berhasil
- [ ] Konfirmasi cancel tidak hapus data

### Security Testing
- [ ] CSRF token terkirim
- [ ] Session validation bekerja
- [ ] Role validation bekerja
- [ ] SQL injection prevention

### Error Handling
- [ ] Error "Data tidak ditemukan"
- [ ] Error "Sudah digunakan di transaksi"
- [ ] Error "Session invalid"
- [ ] Network error handling

### Logging
- [ ] Log penghapusan tercatat
- [ ] Log perubahan status tercatat
- [ ] Log edit tercatat
- [ ] Log berisi: user, timestamp, action

---

## 🎯 Skenario Real World

### Skenario 1: Admin Salah Input Data
```
Problem: Admin salah input jenis sampah "Plastik PETT" (typo)
Solution:
1. Klik tombol Hapus
2. Konfirmasi
3. ✅ Data terhapus
4. Tambah ulang dengan benar: "Plastik PET"
```

### Skenario 2: Jenis Sampah Tidak Digunakan Lagi
```
Problem: Jenis sampah "Kaca" sudah tidak diterima lagi
Solution:
1. Klik tombol Toggle Status
2. ✅ Status jadi Nonaktif
3. User tidak bisa pilih "Kaca" lagi
4. Data lama tetap aman
```

### Skenario 3: Data Kosong (Bug)
```
Problem: Ada data dengan jenis_sampah kosong
Solution:
1. Cari data dengan kolom "Jenis Sampah" kosong
2. Klik tombol Hapus
3. ✅ Data kosong terhapus
4. Problem solved!
```

### Skenario 4: Hapus Data yang Sudah Digunakan
```
Problem: Admin coba hapus "Plastik PET" yang sudah ada 100 transaksi
Solution:
1. Klik tombol Hapus
2. ❌ Error: "Sudah digunakan dalam 100 transaksi"
3. ✅ Data tidak terhapus (aman!)
4. Admin bisa pilih Nonaktifkan sebagai alternatif
```

---

## 🔧 Troubleshooting

### Problem: Tombol Hapus Tidak Muncul
**Solution:**
- Clear browser cache
- Hard refresh (Ctrl + Shift + R)
- Cek console untuk error JavaScript

### Problem: Klik Hapus Tidak Ada Respon
**Solution:**
- Buka browser console (F12)
- Cek error JavaScript
- Pastikan function `deleteHarga()` ada
- Cek network tab untuk request

### Problem: Error "Session Invalid"
**Solution:**
- Logout dan login ulang
- Clear cookies
- Cek session di server

### Problem: Data Tidak Terhapus
**Solution:**
- Cek apakah data sudah digunakan di transaksi
- Cek log error di server
- Cek database connection

---

## 📊 Expected Database Changes

### Sebelum Hapus:
```sql
SELECT * FROM harga_sampah WHERE id = 1;
-- Result: 1 row
```

### Setelah Hapus (Sukses):
```sql
SELECT * FROM harga_sampah WHERE id = 1;
-- Result: 0 rows (data terhapus)

SELECT * FROM harga_log WHERE harga_id = 1 ORDER BY created_at DESC LIMIT 1;
-- Result: Log dengan keterangan "Jenis sampah dihapus"
```

### Setelah Hapus (Gagal - Sudah Digunakan):
```sql
SELECT * FROM harga_sampah WHERE id = 1;
-- Result: 1 row (data masih ada)

SELECT COUNT(*) FROM waste WHERE jenis_sampah = 'Plastik PET';
-- Result: 15 (ada 15 transaksi)
```

---

## ✅ Success Criteria

Fitur dianggap berhasil jika:

1. ✅ Tombol hapus muncul di setiap row tabel
2. ✅ Konfirmasi dialog muncul sebelum hapus
3. ✅ Data yang belum digunakan bisa dihapus
4. ✅ Data yang sudah digunakan TIDAK bisa dihapus
5. ✅ Error message jelas dan informatif
6. ✅ Logging penghapusan tercatat
7. ✅ Session dan CSRF validation bekerja
8. ✅ Data kosong bisa dihapus (bug fix)
9. ✅ Toggle status bekerja sebagai alternatif
10. ✅ Tidak ada error di console

---

## 🚀 Ready to Test!

Sekarang Anda bisa mulai testing fitur hapus jenis sampah. Ikuti langkah-langkah di atas dan centang checklist yang sudah berhasil.

**Happy Testing! 🧪✨**
