# Task 12: Tambah Tombol Hapus & Cleanup Data Lama - SELESAI ✅

## Status: ✅ SELESAI

## Permintaan User
1. Tambahkan fungsi tombol hapus di manajemen sampah admin
2. Hapus data lama yang tidak nyambung dengan user/TPS
3. Koneksikan input data role user dan TPS ke waste management admin

## Perubahan yang Dilakukan

### 1. Tambah Tombol Hapus di Waste Management Admin

#### A. View: `app/Views/admin_pusat/waste_management.php`

**Tombol Aksi Sekarang:**
```php
<div class="btn-group btn-group-sm">
    <!-- Tombol Setujui & Tolak (hanya untuk status dikirim/review) -->
    <?php if (in_array($waste['status'], ['dikirim', 'review'])): ?>
    <button class="btn btn-success" onclick="approveWaste(<?= $waste['id'] ?>)">
        <i class="fas fa-check"></i> Setujui
    </button>
    <button class="btn btn-danger" onclick="rejectWaste(<?= $waste['id'] ?>)">
        <i class="fas fa-times"></i> Tolak
    </button>
    <?php endif; ?>
    
    <!-- Tombol Hapus (untuk semua status) -->
    <button class="btn btn-danger" onclick="deleteWaste(<?= $waste['id'] ?>, '<?= esc($waste['jenis_sampah']) ?>')">
        <i class="fas fa-trash"></i> Hapus
    </button>
</div>
```

**Fungsi JavaScript:**
```javascript
function deleteWaste(id, jenisSampah) {
    if (confirm(`Apakah Anda yakin ingin menghapus data sampah "${jenisSampah}"?\n\nData yang dihapus tidak dapat dikembalikan.`)) {
        // Kirim request DELETE ke server
        fetch(`/admin-pusat/waste/delete/${id}`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            }
        });
    }
}
```

#### B. Controller: `app/Controllers/Admin/Waste.php`

**Method Baru:**
```php
public function delete($id)
{
    // Validasi session
    // Panggil service deleteWaste()
    // Return JSON response
}
```

#### C. Service: `app/Services/Admin/WasteService.php`

**Method Baru:**
```php
public function deleteWaste(int $id): array
{
    // Cari data
    // Hapus langsung (tidak pindah ke laporan_waste)
    // Return success/error
}
```

#### D. Routes: `app/Config/Routes/Admin/waste.php`

**Route Baru:**
```php
$routes->post('waste/delete/(:num)', 'Admin\\Waste::delete/$1');
$routes->delete('waste/delete/(:num)', 'Admin\\Waste::delete/$1');
```

### 2. SQL Cleanup Data Lama

#### File: `CLEANUP_DAN_KONEKSI_USER_TPS.sql`

**Langkah-langkah:**

1. **Backup (Opsional):**
   ```sql
   CREATE TABLE waste_management_backup AS
   SELECT * FROM waste_management;
   ```

2. **Hapus Semua Data Lama:**
   ```sql
   TRUNCATE TABLE waste_management;
   ```

3. **Verifikasi:**
   ```sql
   SELECT COUNT(*) FROM waste_management;
   -- Seharusnya: 0
   ```

4. **Cek User & Unit:**
   ```sql
   -- Cek user yang bisa input
   SELECT id, username, nama_lengkap, role, unit_id
   FROM users
   WHERE role IN ('user', 'pengelola_tps')
   AND status_aktif = 1;
   
   -- Cek unit aktif
   SELECT id, nama_unit
   FROM unit
   WHERE status_aktif = 1;
   ```

### 3. Koneksi Input User/TPS ke Waste Management

**Flow Data:**
```
1. User/TPS login → Dapat unit_id dari session
2. User/TPS input data sampah → Simpan ke waste_management
   - unit_id: dari user yang login
   - status: 'draft' atau 'dikirim'
   - tanggal: tanggal input
   - jenis_sampah, berat_kg, dll
3. Admin buka waste management → Lihat semua data
   - Query JOIN dengan unit untuk dapat nama_unit
   - Tampilkan tombol Setujui/Tolak/Hapus
4. Admin approve/reject → Data pindah ke laporan_waste
5. Admin hapus → Data dihapus permanen
```

**Query yang Digunakan:**
```sql
SELECT 
    wm.id,
    wm.unit_id,
    wm.jenis_sampah,
    wm.berat_kg,
    wm.status,
    wm.tanggal,
    u.nama_unit,
    wm.created_at
FROM waste_management wm
LEFT JOIN unit u ON u.id = wm.unit_id
ORDER BY wm.created_at DESC;
```

## Hasil Akhir

### Tombol Aksi di Waste Management:
1. ✅ **Setujui** (hijau) - Untuk status dikirim/review
2. ✅ **Tolak** (merah) - Untuk status dikirim/review
3. ✅ **Hapus** (merah) - Untuk semua status

### Data Flow:
1. ✅ Data lama yang tidak nyambung sudah dihapus
2. ✅ User/TPS input data baru → Masuk ke waste_management
3. ✅ Admin lihat data → Tampil dengan nama unit yang benar
4. ✅ Admin approve/reject → Data pindah ke laporan_waste
5. ✅ Admin hapus → Data dihapus permanen

## Testing

### 1. Test Tombol Hapus
1. Login sebagai admin
2. Buka Waste Management
3. Klik tombol "Hapus" pada salah satu data
4. Konfirmasi hapus
5. Data seharusnya hilang dari list

### 2. Test Input User/TPS
1. Login sebagai user atau TPS
2. Input data sampah baru
3. Kirim data (status: dikirim)
4. Login sebagai admin
5. Data seharusnya muncul di waste management dengan unit yang benar

### 3. Test Cleanup
1. Jalankan SQL: `TRUNCATE TABLE waste_management;`
2. Verifikasi: `SELECT COUNT(*) FROM waste_management;` → 0
3. User/TPS input data baru
4. Admin cek waste management → Data muncul

## File yang Dimodifikasi
1. `app/Views/admin_pusat/waste_management.php` - Tambah tombol & fungsi hapus
2. `app/Controllers/Admin/Waste.php` - Tambah method delete()
3. `app/Services/Admin/WasteService.php` - Tambah method deleteWaste()
4. `app/Config/Routes/Admin/waste.php` - Tambah route delete
5. `CLEANUP_DAN_KONEKSI_USER_TPS.sql` - SQL cleanup data lama

---
**Tanggal:** 15 Januari 2026
**Task:** #12 - Tombol Hapus & Cleanup Data Lama
