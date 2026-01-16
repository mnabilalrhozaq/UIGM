# Troubleshooting: Data Hilang di Waste Management

## Masalah
Setelah cleanup, data dengan status draft dan dikirim tidak muncul di waste management admin.

## Kemungkinan Penyebab

### 1. Data Benar-Benar Terhapus
Script cleanup menghapus semua data termasuk draft/dikirim.

**Solusi:** Restore dari laporan_waste (jika ada backup)

### 2. Kolom created_by Bernilai NULL
Data ada tapi `created_by` NULL sehingga tidak muncul karena JOIN gagal.

**Solusi:** Update created_by dengan user dari unit yang sama

### 3. Status Tidak Sesuai
Data memiliki status selain 'draft', 'dikirim', 'review'.

**Solusi:** Cek dan update status yang salah

## Langkah Troubleshooting

### Step 1: Cek Apakah Data Masih Ada
```sql
-- Jalankan: CEK_DATA_WASTE_MANAGEMENT.sql
SELECT COUNT(*) as total_data FROM waste_management;

SELECT status, COUNT(*) as jumlah
FROM waste_management
GROUP BY status;
```

**Hasil:**
- Jika total_data = 0 → Data terhapus, lanjut ke Step 2
- Jika total_data > 0 → Data ada, lanjut ke Step 3

### Step 2: Restore Data (Jika Terhapus)
```sql
-- Jalankan: RESTORE_DATA_DARI_LAPORAN.sql
-- Uncomment bagian INSERT untuk restore
```

### Step 3: Cek Kolom created_by
```sql
-- Jalankan: FIX_CREATED_BY_NULL.sql
SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN created_by IS NULL THEN 1 ELSE 0 END) as null_count
FROM waste_management;
```

**Jika null_count > 0:**
```sql
-- Update created_by
UPDATE waste_management wm
INNER JOIN (
    SELECT unit_id, MIN(id) as first_user_id
    FROM users
    WHERE role IN ('user', 'pengelola_tps')
    GROUP BY unit_id
) user_map ON user_map.unit_id = wm.unit_id
SET wm.created_by = user_map.first_user_id
WHERE wm.created_by IS NULL;
```

### Step 4: Verifikasi Query
```sql
-- Test query yang digunakan service
SELECT 
    wm.id,
    wm.created_by,
    wm.unit_id,
    wm.jenis_sampah,
    wm.status,
    u.nama_unit,
    us.nama_lengkap
FROM waste_management wm
LEFT JOIN unit u ON u.id = wm.unit_id
LEFT JOIN users us ON us.id = wm.created_by
WHERE wm.status IN ('draft', 'dikirim', 'review')
ORDER BY wm.created_at DESC
LIMIT 10;
```

### Step 5: Cek Log Error
Buka file log di `writable/logs/` dan cari error terkait waste management.

## Quick Fix

**Jika data masih ada tapi tidak muncul:**

1. **Update created_by yang NULL:**
   ```sql
   UPDATE waste_management wm
   INNER JOIN users u ON u.unit_id = wm.unit_id
   SET wm.created_by = u.id
   WHERE wm.created_by IS NULL
   AND u.role IN ('user', 'pengelola_tps')
   LIMIT 1;
   ```

2. **Refresh halaman waste management**

**Jika data benar-benar terhapus:**

1. Cek apakah ada backup database
2. Restore dari backup
3. Atau minta user/TPS input ulang data

## File SQL Helper
1. `CEK_DATA_WASTE_MANAGEMENT.sql` - Cek status data
2. `FIX_CREATED_BY_NULL.sql` - Fix created_by yang NULL
3. `RESTORE_DATA_DARI_LAPORAN.sql` - Restore dari laporan_waste

## Pencegahan
- Jangan jalankan DELETE tanpa WHERE clause yang jelas
- Selalu backup database sebelum cleanup
- Test query SELECT dulu sebelum DELETE

---
**Catatan:** Service sudah diupdate untuk handle created_by NULL dengan COALESCE
