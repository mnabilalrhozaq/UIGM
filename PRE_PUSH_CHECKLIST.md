# ✅ Pre-Push Checklist

Checklist ini harus dicek sebelum push ke GitHub untuk memastikan keamanan dan kelengkapan.

## 🔐 Security Check

### File Sensitif
- [ ] File `.env` TIDAK ter-track di Git
  ```bash
  git status | grep .env
  # Seharusnya tidak muncul
  ```

- [ ] Tidak ada password hardcoded di source code
  ```bash
  # Cari password di code
  grep -r "password.*=" app/ --include="*.php" | grep -v "password_hash"
  ```

- [ ] Tidak ada API key atau token di code
  ```bash
  grep -r "api_key\|token\|secret" app/ --include="*.php"
  ```

- [ ] Database backup tidak mengandung data sensitif
  - [ ] Tidak ada data user real
  - [ ] Tidak ada email real
  - [ ] Tidak ada nomor telepon real

### Credentials
- [ ] `.env.example` tidak berisi password real
- [ ] Default password sudah didokumentasikan di README
- [ ] Warning untuk ganti password ada di dokumentasi

## 📁 File Structure Check

### Files yang HARUS Ada
- [ ] `README.md` - Dokumentasi utama
- [ ] `.gitignore` - Ignore rules
- [ ] `.gitattributes` - Git attributes
- [ ] `.env.example` - Template environment
- [ ] `composer.json` - Dependencies
- [ ] `SETUP_GITHUB.md` - Panduan GitHub
- [ ] `QUICK_START_GITHUB.md` - Quick start
- [ ] `CHANGELOG.md` - Change log
- [ ] `LICENSE` - License file

### Files yang TIDAK BOLEH Ada
- [ ] `.env` - Environment file (SENSITIF!)
- [ ] `vendor/` - Composer dependencies
- [ ] `writable/logs/*.log` - Log files
- [ ] `writable/cache/*` - Cache files
- [ ] `writable/session/*` - Session files
- [ ] `*.bak` - Backup files
- [ ] `*.backup` - Backup files
- [ ] Database dengan data real

### Folders yang Harus Ada (dengan .gitkeep)
- [ ] `writable/uploads/.gitkeep`
- [ ] `writable/cache/.gitkeep`
- [ ] `writable/logs/.gitkeep`
- [ ] `writable/session/.gitkeep`

## 📝 Documentation Check

### README.md
- [ ] Deskripsi project jelas
- [ ] Fitur utama terdokumentasi
- [ ] Cara instalasi lengkap
- [ ] Default user accounts terdokumentasi
- [ ] Troubleshooting ada
- [ ] Contact info ada

### Code Documentation
- [ ] Controller ada docblock
- [ ] Model ada docblock
- [ ] Service ada docblock
- [ ] Function penting ada comment

## 🧪 Testing Check

### Functionality
- [ ] Login berfungsi untuk semua role
- [ ] CRUD sampah berfungsi
- [ ] Pagination berfungsi
- [ ] Filter berfungsi
- [ ] Export PDF berfungsi
- [ ] Review/approval berfungsi

### Security
- [ ] CSRF protection aktif
- [ ] Session timeout berfungsi
- [ ] Password di-hash
- [ ] SQL injection protected
- [ ] XSS protected

## 🔧 Configuration Check

### .gitignore
- [ ] `.env` di-ignore
- [ ] `vendor/` di-ignore
- [ ] `writable/logs/*` di-ignore
- [ ] `writable/cache/*` di-ignore
- [ ] `writable/session/*` di-ignore
- [ ] `*.log` di-ignore

### .env.example
- [ ] Semua variable ada
- [ ] Tidak ada value sensitif
- [ ] Comment/dokumentasi jelas
- [ ] Database name generic

## 📊 Database Check

### SQL Files
- [ ] Database schema ada
- [ ] Sample data aman (tidak sensitif)
- [ ] CREATE TABLE statements lengkap
- [ ] Indexes terdefinisi
- [ ] Foreign keys terdefinisi

### Documentation
- [ ] `database/README.md` ada
- [ ] Table structure terdokumentasi
- [ ] Relationships terdokumentasi
- [ ] Sample queries ada

## 🎨 Code Quality Check

### Coding Standards
- [ ] Indentation konsisten
- [ ] Naming convention konsisten
- [ ] No debug code (var_dump, print_r, dd)
- [ ] No commented code yang tidak perlu
- [ ] No TODO yang critical

### Performance
- [ ] No N+1 queries
- [ ] Indexes pada kolom yang sering di-query
- [ ] Pagination untuk data besar
- [ ] Cache untuk data static

## 🚀 Git Check

### Repository
- [ ] Git initialized
  ```bash
  git status
  ```

- [ ] Remote repository configured
  ```bash
  git remote -v
  ```

- [ ] Branch name correct (main/master)
  ```bash
  git branch
  ```

### Commits
- [ ] Commit message jelas dan deskriptif
- [ ] Tidak ada commit dengan message "test" atau "fix"
- [ ] Commit history clean (no sensitive data)

## 📦 Dependencies Check

### Composer
- [ ] `composer.json` ada
- [ ] `composer.lock` ada
- [ ] Dependencies up to date
  ```bash
  composer outdated
  ```

### Vendor
- [ ] `vendor/` di-ignore
- [ ] Tidak di-commit ke Git

## 🌐 Production Ready Check

### Environment
- [ ] Production config di `.env.example`
- [ ] Debug mode OFF untuk production
- [ ] Error reporting sesuai environment
- [ ] HTTPS force untuk production

### Security Headers
- [ ] CSRF protection enabled
- [ ] XSS protection enabled
- [ ] Content Security Policy configured
- [ ] HSTS configured (production)

## ✅ Final Check

Sebelum push, jalankan:

```bash
# 1. Cek status
git status

# 2. Cek file yang akan di-commit
git diff --cached

# 3. Cek .env tidak ter-track
git ls-files | grep .env
# Seharusnya hanya .env.example

# 4. Cek vendor tidak ter-track
git ls-files | grep vendor
# Seharusnya kosong

# 5. Cek log files tidak ter-track
git ls-files | grep "\.log$"
# Seharusnya kosong
```

## 🎯 Ready to Push?

Jika semua checklist di atas sudah ✅, maka:

```bash
git add .
git commit -m "Initial commit: UIGM POLBAN Waste Management System"
git push origin main
```

## ⚠️ Emergency - Jika Ada Masalah

### File Sensitif Ter-commit

```bash
# Remove from staging
git reset HEAD .env

# Remove from Git history
git rm --cached .env
git commit -m "remove sensitive file"
```

### Perlu Rollback

```bash
# Undo last commit (keep changes)
git reset --soft HEAD~1

# Undo last commit (discard changes)
git reset --hard HEAD~1
```

### Force Push (HATI-HATI!)

```bash
# Only if you're sure!
git push origin main --force
```

---

## 📞 Need Help?

Jika ada yang tidak jelas:
1. Baca `SETUP_GITHUB.md` untuk detail
2. Baca `QUICK_START_GITHUB.md` untuk quick start
3. Cek dokumentasi Git: https://git-scm.com/doc

---

**Checklist ini dibuat untuk memastikan push ke GitHub aman dan lengkap! 🔒**
