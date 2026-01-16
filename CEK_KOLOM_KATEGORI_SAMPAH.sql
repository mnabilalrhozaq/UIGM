-- Cek struktur kolom kategori_sampah
USE eksperimen;

SHOW COLUMNS FROM waste_management LIKE 'kategori_sampah';

-- Cek nilai yang ada
SELECT DISTINCT kategori_sampah FROM waste_management;
