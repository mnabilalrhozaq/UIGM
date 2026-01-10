# ✅ Checklist Perapihan Project CodeIgniter 4

## 🎯 Pre-Reorganization Checklist

### 📋 Persiapan
- [ ] Backup project lengkap
- [ ] Commit semua perubahan ke Git
- [ ] Catat semua custom path yang mungkin hardcoded
- [ ] Test aplikasi sebelum reorganisasi

### 📁 Analisis File Tercecer
- [ ] Identifikasi file PHP di root directory
- [ ] Identifikasi file SQL di root directory  
- [ ] Identifikasi file dokumentasi di root directory
- [ ] Identifikasi file asset di folder salah
- [ ] Identifikasi file config custom di luar app/Config/

## 🚀 Reorganization Process

### 📦 Struktur Folder
- [ ] Buat folder database/sql/
- [ ] Buat folder database/sql/patches/
- [ ] Buat folder database/sql/exports/
- [ ] Buat folder docs/development/
- [ ] Buat folder docs/fixes/
- [ ] Buat folder docs/api/
- [ ] Buat folder docs/user-guide/
- [ ] Buat folder scripts/setup/
- [ ] Buat folder scripts/maintenance/
- [ ] Buat folder scripts/deployment/
- [ ] Buat folder public/assets/css/
- [ ] Buat folder public/assets/js/
- [ ] Buat folder public/assets/img/
- [ ] Buat folder public/assets/vendor/

### 🗂️ Pemindahan File

#### Database Files
- [ ] Pindahkan database_export.sql → database/sql/exports/
- [ ] Pindahkan database_*.sql → database/sql/patches/
- [ ] Rename dengan naming convention yang konsisten

#### Documentation Files  
- [ ] Pindahkan IMPLEMENTASI_*.md → docs/development/
- [ ] Pindahkan PERBAIKAN_*.md → docs/fixes/
- [ ] Pindahkan SISTEM_*.md → docs/development/
- [ ] Rename dengan lowercase dan underscore

#### Script Files
- [ ] Pindahkan cleanup_*.php → scripts/maintenance/
- [ ] Pindahkan organize_*.php → scripts/maintenance/
- [ ] Pindahkan setup_*.php → scripts/setup/

#### Test Files
- [ ] Pindahkan *_test.php → tests/integration/
- [ ] Pindahkan test_*.php → tests/integration/

### 🧹 Cleanup
- [ ] Hapus file temporary yang tidak digunakan
- [ ] Hapus file duplicate
- [ ] Hapus file backup lama
- [ ] Review file preload.php (hapus jika tidak digunakan)

## 🔧 Post-Reorganization Tasks

### 📝 Update Konfigurasi
- [ ] Update .gitignore
- [ ] Update composer.json jika ada path custom
- [ ] Update phpunit.xml.dist jika ada path test custom
- [ ] Buat .gitkeep untuk folder kosong

### 🔍 Code Review
- [ ] Cari hardcoded path di Controllers
- [ ] Cari hardcoded path di Models  
- [ ] Cari hardcoded path di Views
- [ ] Cari hardcoded path di Config files
- [ ] Update include/require path jika ada

### 🧪 Testing
- [ ] Test login functionality
- [ ] Test dashboard admin pusat
- [ ] Test dashboard user
- [ ] Test file upload/download
- [ ] Test routing semua halaman
- [ ] Test API endpoints
- [ ] Test notification system

### 📊 Asset Management
- [ ] Pindahkan CSS files ke public/assets/css/
- [ ] Pindahkan JS files ke public/assets/js/
- [ ] Pindahkan images ke public/assets/img/
- [ ] Update path asset di views
- [ ] Test loading asset di browser

## 📋 Quality Assurance

### 🎨 Code Standards
- [ ] Konsistensi penamaan file (PascalCase untuk Classes)
- [ ] Konsistensi penamaan folder (lowercase)
- [ ] Konsistensi namespace sesuai folder structure
- [ ] Konsistensi indentasi dan formatting

### 📚 Documentation
- [ ] Update README.md dengan struktur baru
- [ ] Update installation guide
- [ ] Update deployment guide  
- [ ] Buat documentation untuk developer baru

### 🔒 Security Check
- [ ] Pastikan tidak ada file sensitive di public/
- [ ] Pastikan .env tidak di-commit
- [ ] Pastikan folder writable/ tidak accessible via web
- [ ] Review .htaccess files

## 🚀 Pre-Commit Checklist

### 🧪 Final Testing
- [ ] Test di environment development
- [ ] Test di environment staging (jika ada)
- [ ] Test dengan data production (copy)
- [ ] Test performance (loading time)

### 📦 Git Management
- [ ] Review semua file changes
- [ ] Commit dengan message yang descriptive
- [ ] Tag version jika major reorganization
- [ ] Update branch protection rules jika perlu

### 📖 Documentation Update
- [ ] Update CHANGELOG.md
- [ ] Update API documentation
- [ ] Update user guide jika ada perubahan UI
- [ ] Notify team tentang perubahan struktur

## 🎯 Maintenance Guidelines

### 📁 File Organization Rules
- [ ] Buat guideline untuk developer baru
- [ ] Setup pre-commit hooks untuk check file location
- [ ] Regular review struktur folder (monthly)
- [ ] Training team tentang best practices

### 🔄 Continuous Improvement
- [ ] Monitor untuk file yang tercecer lagi
- [ ] Review dan update struktur sesuai kebutuhan
- [ ] Feedback dari team untuk improvement
- [ ] Update tools dan scripts sesuai kebutuhan

## ⚠️ Common Pitfalls to Avoid

### 🚫 Jangan Lakukan
- [ ] Jangan pindahkan file core CI4 (system/, vendor/)
- [ ] Jangan ubah struktur app/ yang sudah standard CI4
- [ ] Jangan hardcode path absolute di kode
- [ ] Jangan commit file sensitive (.env, database credentials)

### ✅ Best Practices
- [ ] Gunakan helper CI4 untuk path (base_url(), APPPATH, etc.)
- [ ] Konsisten dengan naming convention
- [ ] Dokumentasikan setiap perubahan major
- [ ] Test setiap perubahan sebelum commit

## 📞 Support & Resources

### 📚 References
- [ ] CodeIgniter 4 User Guide
- [ ] PSR-4 Autoloading Standard
- [ ] Git Best Practices
- [ ] PHP Best Practices

### 🆘 Troubleshooting
- [ ] Backup selalu tersedia
- [ ] Log error untuk debugging
- [ ] Contact senior developer jika stuck
- [ ] Document solution untuk future reference

---

**💡 Tips:** Lakukan reorganisasi secara bertahap, test setiap step, dan selalu backup sebelum perubahan major!