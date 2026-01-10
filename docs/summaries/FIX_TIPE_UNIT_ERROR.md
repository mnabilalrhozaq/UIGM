# 🚨 FIX ERROR: Unknown column 'unit.tipe_unit'

## ❌ **MASALAH:**

```
Unknown column 'unit.tipe_unit' in 'field list'
```

## ✅ **SOLUSI CEPAT:**

### **Opsi 1: Jalankan Patch Database**

1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Pilih database `uigm_polban`
3. Klik tab **"SQL"**
4. Copy-paste isi file `database_patch_add_missing_columns.sql`
5. Klik **"Go"**

### **Opsi 2: Import Ulang Database Lengkap**

1. **Drop database lama:**

   ```sql
   DROP DATABASE IF EXISTS uigm_polban;
   ```

2. **Import backup baru:**
   - File: `database_backup_uigm_polban.sql` (sudah diperbaiki)
   - Database ini sudah include kolom `tipe_unit`

### **Opsi 3: Manual ALTER TABLE**

```sql
USE uigm_polban;

-- Add missing columns
ALTER TABLE `unit`
ADD COLUMN `tipe_unit` ENUM('fakultas','jurusan','unit_kerja','lembaga') NOT NULL DEFAULT 'fakultas' AFTER `nama_unit`,
ADD COLUMN `parent_id` INT(11) UNSIGNED DEFAULT NULL AFTER `tipe_unit`,
ADD COLUMN `admin_unit_id` INT(11) UNSIGNED DEFAULT NULL AFTER `parent_id`;

-- Update existing data
UPDATE `unit` SET `tipe_unit` = 'lembaga' WHERE `kode_unit` = 'POLBAN';
UPDATE `unit` SET `tipe_unit` = 'jurusan', `parent_id` = 1 WHERE `kode_unit` IN ('JTE', 'JTM', 'JTS', 'JTIK');

-- Add foreign keys
ALTER TABLE `unit`
ADD CONSTRAINT `fk_unit_parent` FOREIGN KEY (`parent_id`) REFERENCES `unit` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
ADD CONSTRAINT `fk_unit_admin` FOREIGN KEY (`admin_unit_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
```

## 🔍 **VERIFIKASI:**

Jalankan query ini untuk memastikan kolom sudah ada:

```sql
DESCRIBE unit;
```

Hasilnya harus menampilkan kolom:

- ✅ `tipe_unit`
- ✅ `parent_id`
- ✅ `admin_unit_id`

## 📋 **STRUKTUR UNIT YANG BENAR:**

| ID  | Kode Unit | Nama Unit                  | Tipe Unit | Parent ID |
| --- | --------- | -------------------------- | --------- | --------- |
| 1   | POLBAN    | Politeknik Negeri Bandung  | lembaga   | NULL      |
| 2   | JTE       | Jurusan Teknik Elektro     | jurusan   | 1         |
| 3   | JTM       | Jurusan Teknik Mesin       | jurusan   | 1         |
| 4   | JTS       | Jurusan Teknik Sipil       | jurusan   | 1         |
| 5   | JTIK      | Jurusan Teknik Informatika | jurusan   | 1         |

## ✅ **SETELAH FIX:**

1. **Test koneksi:** Jalankan `test_database_connection.php`
2. **Login website:** `http://localhost/eksperimen/`
3. **Test dropdown:** Login sebagai admin unit dan test dropdown sampah organik

---

**💡 TIP:** Gunakan backup database yang sudah diperbaiki (`database_backup_uigm_polban.sql`) untuk instalasi baru agar tidak perlu patch lagi.
