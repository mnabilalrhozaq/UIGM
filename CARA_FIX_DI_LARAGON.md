# 🔧 Cara Fix Error di Laragon - Step by Step

## ⚠️ PENTING!

Anda menggunakan **Laragon**, bukan XAMPP. Database Anda sudah ada dan sudah berisi data.

**JANGAN import `eksperimen_fixed.sql`!** Itu akan menghapus semua data Anda!

---

## ✅ Langkah Fix yang Benar

### STEP 1: Buka phpMyAdmin

1. Buka browser
2. Ketik: `http://localhost/phpmyadmin`
3. Pilih database `eksperimen` di sidebar kiri
4. Klik tab "SQL"

---

### STEP 2: Jalankan Query Satu Per Satu

**PENTING:** Jalankan query **SATU PER SATU**, jangan sekaligus!

#### Query 1: Hapus Data Kosong

Copy-paste query ini, klik "Go":

```sql
DELETE FROM master_harga_sampah 
WHERE nama_jenis = '' OR nama_jenis IS NULL OR jenis_sampah = '';
```

✅ **Expected:** "Query OK, X rows affected" (X = jumlah data kosong yang dihapus)

---

#### Query 2: Hapus UNIQUE Constraint

Copy-paste query ini, klik "Go":

```sql
ALTER TABLE master_harga_sampah DROP INDEX unique_jenis_sampah;
```

✅ **Expected:** "Query OK, 0 rows affected"

❌ **Jika error "Can't DROP 'unique_jenis_sampah'":**
- Artinya constraint sudah tidak ada (bagus!)
- **Skip query ini**, lanjut ke Query 3

---

#### Query 3: Tambah Index Biasa

Copy-paste query ini, klik "Go":

```sql
ALTER TABLE master_harga_sampah ADD INDEX idx_jenis_sampah (jenis_sampah);
```

✅ **Expected:** "Query OK, 0 rows affected"

❌ **Jika error "Duplicate key name 'idx_jenis_sampah'":**
- Artinya index sudah ada (bagus!)
- **Skip query ini**, lanjut ke Query 4

---

#### Query 4: Cek Duplikasi Nama

Copy-paste query ini, klik "Go":

```sql
SELECT nama_jenis, COUNT(*) as jumlah
FROM master_harga_sampah
GROUP BY nama_jenis
HAVING COUNT(*) > 1;
```

✅ **Jika tidak ada hasil (0 rows):**
- Bagus! Tidak ada duplikasi
- **Skip Query 5**, lanjut ke Query 6

❌ **Jika ada hasil (ada nama yang duplikat):**
- Lanjut ke Query 5

---

#### Query 5: Hapus Duplikat (Jika Ada)

**Hanya jalankan jika Query 4 ada hasil!**

Copy-paste query ini, klik "Go":

```sql
DELETE t1 FROM master_harga_sampah t1
INNER JOIN master_harga_sampah t2 
WHERE t1.id > t2.id AND t1.nama_jenis = t2.nama_jenis;
```

✅ **Expected:** "Query OK, X rows affected" (X = jumlah duplikat yang dihapus)

---

#### Query 6: Tambah UNIQUE pada Nama

Copy-paste query ini, klik "Go":

```sql
ALTER TABLE master_harga_sampah ADD UNIQUE KEY unique_nama_jenis (nama_jenis);
```

✅ **Expected:** "Query OK, 0 rows affected"

❌ **Jika error "Duplicate key name 'unique_nama_jenis'":**
- Artinya constraint sudah ada (bagus!)
- **Skip query ini**, lanjut ke Query 7

❌ **Jika error "Duplicate entry":**
- Kembali ke Query 4
- Cek data duplikat
- Hapus manual dengan query:
  ```sql
  DELETE FROM master_harga_sampah WHERE id = X; -- ganti X dengan ID yang mau dihapus
  ```
- Jalankan ulang Query 6

---

#### Query 7: Verifikasi Hasil

Copy-paste query ini, klik "Go":

```sql
SELECT * FROM master_harga_sampah ORDER BY id;
```

✅ **Expected:**
- Tidak ada data dengan `jenis_sampah` kosong
- Tidak ada data dengan `nama_jenis` kosong
- Semua data lengkap

Lalu jalankan query ini:

```sql
SHOW INDEX FROM master_harga_sampah;
```

✅ **Expected:**
- `PRIMARY` (id)
- `unique_nama_jenis` (nama_jenis) ← UNIQUE
- `idx_jenis_sampah` (jenis_sampah) ← INDEX biasa (bukan UNIQUE)

---

### STEP 3: Clear Browser Cache

1. **Tekan `Ctrl + Shift + Delete`**
2. **Pilih:**
   - ✅ Cached images and files
   - ✅ Cookies and other site data
3. **Time range:** All time
4. **Klik "Clear data"**

5. **Hard Refresh:**
   - Tekan `Ctrl + Shift + R`

---

### STEP 4: Test Tambah Data

1. **Buka aplikasi:** `http://localhost:8080/admin-pusat/manajemen-harga`

2. **Klik "Tambah Jenis Sampah"**

3. **Isi form:**
   - **Kategori Utama:** `Logam`
   - **Sub Kategori:** `Tembaga`
   - **Nama Lengkap:** `Kabel Tembaga Bekas`
   - **Harga:** `4000`
   - **Satuan:** `kg`
   - **Dapat Dijual:** ✅
   - **Status Aktif:** ✅

4. **Klik "Simpan"**

✅ **Expected:** "Jenis sampah berhasil ditambahkan"

5. **Test tambah data Logam kedua:**
   - **Kategori Utama:** `Logam`
   - **Nama Lengkap:** `Aluminium Kaleng Bekas`
   - **Harga:** `3000`

6. **Klik "Simpan"**

✅ **Expected:** "Jenis sampah berhasil ditambahkan" (tidak ada error "Duplicate entry"!)

---

## 🎯 Checklist

- [ ] Query 1: Hapus data kosong (berhasil)
- [ ] Query 2: Hapus UNIQUE constraint (berhasil atau skip)
- [ ] Query 3: Tambah index biasa (berhasil atau skip)
- [ ] Query 4: Cek duplikasi (tidak ada hasil)
- [ ] Query 5: Hapus duplikat (skip jika tidak ada)
- [ ] Query 6: Tambah UNIQUE pada nama (berhasil atau skip)
- [ ] Query 7: Verifikasi (data lengkap, index benar)
- [ ] Clear browser cache
- [ ] Test tambah data Logam pertama (berhasil)
- [ ] Test tambah data Logam kedua (berhasil, tidak error)

---

## 🚨 Troubleshooting

### Error: "Can't DROP 'unique_jenis_sampah'"

**Artinya:** Constraint sudah tidak ada

**Solusi:** Skip Query 2, lanjut ke Query 3

---

### Error: "Duplicate key name 'idx_jenis_sampah'"

**Artinya:** Index sudah ada

**Solusi:** Skip Query 3, lanjut ke Query 4

---

### Error: "Duplicate key name 'unique_nama_jenis'"

**Artinya:** UNIQUE constraint sudah ada

**Solusi:** Skip Query 6, lanjut ke Query 7

---

### Error: "Duplicate entry 'Kabel' for key 'unique_nama_jenis'"

**Artinya:** Ada data dengan nama yang sama

**Solusi:**

1. Cek data duplikat:
   ```sql
   SELECT * FROM master_harga_sampah WHERE nama_jenis = 'Kabel';
   ```

2. Hapus yang ID lebih besar:
   ```sql
   DELETE FROM master_harga_sampah WHERE id = 7; -- sesuaikan ID
   ```

3. Jalankan ulang Query 6

---

### Masih Error "Duplicate entry 'Logam'" Saat Tambah Data

**Artinya:** Query 2 belum dijalankan atau gagal

**Solusi:**

1. Cek index yang ada:
   ```sql
   SHOW INDEX FROM master_harga_sampah;
   ```

2. Jika masih ada `unique_jenis_sampah`, hapus manual:
   ```sql
   ALTER TABLE master_harga_sampah DROP INDEX unique_jenis_sampah;
   ```

3. Test tambah data lagi

---

## ✅ Selesai!

Setelah semua query berhasil, Anda bisa:

✅ Menambah beberapa jenis sampah dengan kategori yang sama
✅ Tidak ada lagi error "Duplicate entry"
✅ Data kosong sudah terhapus
✅ Sistem berjalan normal

**Happy coding! 🚀**
