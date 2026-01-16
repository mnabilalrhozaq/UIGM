# Cara Menjalankan Perubahan Database: Jenis Sampah

## Masalah
Admin tidak bisa menambahkan jenis sampah baru karena field `jenis_sampah` di database masih menggunakan tipe **ENUM** yang hanya menerima nilai:
- Plastik
- Kertas
- Logam
- Organik
- Residu

## Solusi
Ubah field `jenis_sampah` dari **ENUM** menjadi **VARCHAR(100)** agar bisa menerima nilai bebas.

## Langkah-langkah

### 1. Buka phpMyAdmin atau MySQL Client
- **Laragon**: Klik "Database" → phpMyAdmin
- **XAMPP**: Buka browser → `http://localhost/phpmyadmin`

### 2. Pilih Database
- Pilih database `eksperimen`

### 3. Jalankan SQL Script

#### Opsi A: Menggunakan File SQL
1. Klik tab "Import"
2. Pilih file: `database/sql/patches/ALTER_JENIS_SAMPAH_TO_VARCHAR.sql`
3. Klik "Go"

#### Opsi B: Copy-Paste SQL
1. Klik tab "SQL"
2. Copy-paste script berikut:

```sql
-- Ubah field jenis_sampah dari ENUM ke VARCHAR(100)
ALTER TABLE `master_harga_sampah` 
MODIFY COLUMN `jenis_sampah` VARCHAR(100) NOT NULL COMMENT 'Kategori sampah (bebas diisi)';
```

3. Klik "Go"

### 4. Verifikasi Perubahan
Jalankan query ini untuk memastikan perubahan berhasil:

```sql
DESCRIBE `master_harga_sampah`;
```

Pastikan field `jenis_sampah` sekarang bertipe **varchar(100)**.

## Hasil
Setelah perubahan ini:
- ✅ Admin bisa menambahkan kategori sampah baru secara bebas
- ✅ Tidak terbatas pada 5 kategori saja
- ✅ Contoh kategori baru yang bisa ditambahkan:
  - Elektronik
  - Kaca
  - Tekstil
  - Kayu
  - Baterai
  - dll.

## Catatan Penting
- Data yang sudah ada **TIDAK AKAN HILANG**
- Perubahan ini **AMAN** dan tidak merusak data
- Setelah perubahan, langsung bisa digunakan tanpa restart aplikasi
- Jika ada error, hubungi developer

## Troubleshooting

### Error: "Table doesn't exist"
**Solusi**: Pastikan Anda sudah memilih database `eksperimen`

### Error: "Access denied"
**Solusi**: Pastikan user MySQL Anda memiliki privilege ALTER TABLE

### Ingin Rollback?
Jika ingin kembali ke ENUM (tidak disarankan):
```sql
ALTER TABLE `master_harga_sampah` 
MODIFY COLUMN `jenis_sampah` ENUM('Plastik','Kertas','Logam','Organik','Residu') NOT NULL;
```

**PERINGATAN**: Rollback akan gagal jika sudah ada data dengan kategori di luar ENUM!
