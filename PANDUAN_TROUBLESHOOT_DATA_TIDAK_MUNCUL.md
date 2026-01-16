# Panduan Troubleshoot: Data Tidak Muncul di Waste Management

## Langkah 1: Cek Apakah Ada Data di Database

Jalankan query ini di phpMyAdmin:
```sql
SELECT COUNT(*) as total FROM waste_management;
```

**Hasil:**
- Jika `total = 0` → Tidak ada data, lanjut ke Langkah 2
- Jika `total > 0` → Ada data, lanjut ke Langkah 3

---

## Langkah 2: Insert Data Test (Jika Tidak Ada Data)

1. **Cek unit yang tersedia:**
   ```sql
   SELECT id, nama_unit FROM unit WHERE status_aktif = 1;
   ```

2. **Insert data test:**
   ```sql
   INSERT INTO waste_management (
       unit_id,
       tanggal,
       jenis_sampah,
       satuan,
       jumlah,
       berat_kg,
       gedung,
       kategori_sampah,
       nilai_rupiah,
       status,
       created_at,
       updated_at
   ) VALUES 
   (1, CURDATE(), 'Plastik', 'kg', 10.5, 10.5, 'Gedung A', 'bisa_dijual', 52500, 'draft', NOW(), NOW());
   ```
   
   **Catatan:** Ganti `unit_id = 1` dengan ID unit yang ada di database kamu.

3. **Verifikasi:**
   ```sql
   SELECT COUNT(*) FROM waste_management;
   ```

4. **Refresh halaman waste management**

---

## Langkah 3: Cek Query dengan JOIN (Jika Ada Data)

Jalankan query yang sama dengan yang digunakan service:
```sql
SELECT 
    waste_management.*,
    unit.nama_unit
FROM waste_management
LEFT JOIN unit ON unit.id = waste_management.unit_id
ORDER BY waste_management.created_at DESC
LIMIT 10;
```

**Hasil:**
- Jika query berhasil dan ada data → Masalah di service/controller
- Jika query error → Masalah di struktur tabel

---

## Langkah 4: Cek Log Error

1. Buka folder `writable/logs/`
2. Cari file `log-YYYY-MM-DD.log` (tanggal hari ini)
3. Cari error dengan keyword:
   - `Admin Waste Controller`
   - `Admin - Getting waste list`
   - `Error in getWasteList`

**Contoh log yang benar:**
```
INFO - Admin Waste Controller - Starting index...
INFO - Admin - Getting waste list with unit names...
INFO - Admin - Total data in waste_management: 3
INFO - Admin - Query found 3 records (no filter)
INFO - Admin Waste Controller - Service returned: 3 records
```

---

## Langkah 5: Test Query Langsung di Service

Buka file `app/Services/Admin/WasteService.php` dan cek method `getWasteList()`.

Query yang digunakan:
```php
$result = $db->table('waste_management')
    ->select('waste_management.*, unit.nama_unit')
    ->join('unit', 'unit.id = waste_management.unit_id', 'left')
    ->orderBy('waste_management.created_at', 'DESC')
    ->limit(100)
    ->get()
    ->getResultArray();
```

---

## Langkah 6: Cek View

Buka file `app/Views/admin_pusat/waste_management.php` dan cek:

1. **Apakah ada error di view?**
   ```php
   <?php if (!empty($waste_list)): ?>
       <!-- Tampilkan data -->
   <?php else: ?>
       <div class="empty-state">
           <p>Belum ada data sampah yang perlu direview.</p>
       </div>
   <?php endif; ?>
   ```

2. **Cek variabel `$waste_list`:**
   Tambahkan debug di view (sementara):
   ```php
   <?php 
   echo '<pre>';
   var_dump($waste_list);
   echo '</pre>';
   ?>
   ```

---

## Langkah 7: Hard Refresh Browser

1. Tekan `Ctrl + Shift + R` (Windows/Linux)
2. Atau `Cmd + Shift + R` (Mac)
3. Atau buka Incognito/Private mode

---

## Quick Fix: Insert Data Test & Refresh

**Jalankan SQL ini:**
```sql
-- 1. Cek unit
SELECT id, nama_unit FROM unit LIMIT 1;

-- 2. Insert data test (ganti unit_id sesuai hasil query 1)
INSERT INTO waste_management (
    unit_id, tanggal, jenis_sampah, satuan, jumlah, berat_kg, 
    gedung, kategori_sampah, nilai_rupiah, status, created_at, updated_at
) VALUES 
(1, CURDATE(), 'Plastik', 'kg', 10, 10, 'Gedung A', 'bisa_dijual', 50000, 'draft', NOW(), NOW());

-- 3. Verifikasi
SELECT 
    wm.id, wm.unit_id, u.nama_unit, wm.jenis_sampah, wm.status
FROM waste_management wm
LEFT JOIN unit u ON u.id = wm.unit_id;
```

**Kemudian:**
1. Refresh halaman waste management (Ctrl + F5)
2. Data seharusnya muncul

---

## Jika Masih Tidak Muncul

Kirim screenshot:
1. Hasil query `SELECT COUNT(*) FROM waste_management;`
2. Hasil query dengan JOIN
3. Log error dari `writable/logs/`
4. Screenshot halaman waste management

---

**File SQL Helper:**
- `DEBUG_WASTE_MANAGEMENT.sql` - Query untuk debug
- `INSERT_DATA_TEST.sql` - Insert data test
- `CLEANUP_DAN_KONEKSI_USER_TPS.sql` - Cleanup data lama
