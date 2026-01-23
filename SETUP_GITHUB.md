# 🚀 Panduan Setup GitHub untuk Project UIGM POLBAN

Panduan lengkap untuk push project ke GitHub sebagai backup.

## 📋 Persiapan

### 1. Pastikan Git Terinstall

Cek apakah Git sudah terinstall:
```bash
git --version
```

Jika belum, download dari: https://git-scm.com/downloads

### 2. Konfigurasi Git (Jika Belum)

```bash
git config --global user.name "Nama Anda"
git config --global user.email "email@anda.com"
```

### 3. Buat Akun GitHub

Jika belum punya akun GitHub, daftar di: https://github.com/signup

## 🔧 Setup Repository Lokal

### 1. Inisialisasi Git (Jika Belum)

Di folder project, jalankan:
```bash
git init
```

### 2. Cek Status File

```bash
git status
```

Pastikan file-file penting tidak ter-ignore. File yang HARUS di-ignore:
- `.env` (berisi kredensial database)
- `vendor/` (dependencies, akan di-install via composer)
- `writable/logs/*` (log files)
- `writable/cache/*` (cache files)
- `writable/session/*` (session files)

### 3. Add Files ke Git

```bash
# Add semua file kecuali yang di .gitignore
git add .

# Atau add file tertentu
git add app/
git add public/
git add README.md
```

### 4. Commit Pertama

```bash
git commit -m "Initial commit: UIGM POLBAN Waste Management System"
```

## 🌐 Setup Repository di GitHub

### 1. Buat Repository Baru di GitHub

1. Login ke GitHub
2. Klik tombol **"+"** di pojok kanan atas
3. Pilih **"New repository"**
4. Isi form:
   - **Repository name**: `uigm-polban` (atau nama lain)
   - **Description**: "Sistem Manajemen Sampah UI GreenMetric POLBAN"
   - **Visibility**: 
     - **Private** (jika tidak ingin publik)
     - **Public** (jika ingin open source)
   - **JANGAN** centang "Initialize with README" (karena sudah ada)
5. Klik **"Create repository"**

### 2. Connect ke Remote Repository

Setelah repository dibuat, GitHub akan menampilkan instruksi. Jalankan:

```bash
# Tambahkan remote repository
git remote add origin https://github.com/username/uigm-polban.git

# Atau jika menggunakan SSH
git remote add origin git@github.com:username/uigm-polban.git
```

Ganti `username` dengan username GitHub Anda.

### 3. Push ke GitHub

```bash
# Push branch main/master
git branch -M main
git push -u origin main
```

Jika diminta login:
- **Username**: username GitHub Anda
- **Password**: gunakan **Personal Access Token** (bukan password akun)

## 🔑 Setup Personal Access Token (PAT)

GitHub tidak lagi menerima password biasa untuk push. Gunakan PAT:

### 1. Buat Personal Access Token

1. Login ke GitHub
2. Klik foto profil → **Settings**
3. Scroll ke bawah → **Developer settings**
4. Klik **Personal access tokens** → **Tokens (classic)**
5. Klik **Generate new token** → **Generate new token (classic)**
6. Isi form:
   - **Note**: "UIGM POLBAN Project"
   - **Expiration**: 90 days (atau sesuai kebutuhan)
   - **Select scopes**: centang **repo** (full control)
7. Klik **Generate token**
8. **COPY TOKEN** dan simpan di tempat aman (tidak akan ditampilkan lagi!)

### 2. Gunakan Token untuk Push

Saat diminta password, paste token yang sudah di-copy.

### 3. Simpan Kredensial (Opsional)

Agar tidak perlu input token setiap kali:

**Windows:**
```bash
git config --global credential.helper wincred
```

**Mac:**
```bash
git config --global credential.helper osxkeychain
```

**Linux:**
```bash
git config --global credential.helper store
```

## 📦 File yang Harus Ada di GitHub

### ✅ File yang DI-PUSH:
- `app/` - Source code aplikasi
- `public/` - Assets (CSS, JS, images)
- `database/` - SQL files (struktur database)
- `docs/` - Dokumentasi
- `README.md` - Dokumentasi utama
- `.env.example` - Template environment
- `.gitignore` - Daftar file yang di-ignore
- `.gitattributes` - Git attributes
- `composer.json` - Dependencies
- `.htaccess` - Apache config
- `spark` - CLI tool

### ❌ File yang TIDAK DI-PUSH:
- `.env` - Kredensial database (SENSITIF!)
- `vendor/` - Dependencies (install via composer)
- `writable/logs/*` - Log files
- `writable/cache/*` - Cache files
- `writable/session/*` - Session files
- `writable/uploads/*` - Uploaded files (kecuali .gitkeep)
- `*.log` - Log files
- `*.bak` - Backup files

## 🔄 Workflow Sehari-hari

### 1. Sebelum Mulai Kerja

```bash
# Pull perubahan terbaru dari GitHub
git pull origin main
```

### 2. Setelah Membuat Perubahan

```bash
# Cek file yang berubah
git status

# Add file yang berubah
git add .

# Commit dengan pesan yang jelas
git commit -m "feat: tambah fitur pagination harga sampah"

# Push ke GitHub
git push origin main
```

### 3. Format Commit Message

Gunakan format yang jelas:
- `feat:` - Fitur baru
- `fix:` - Bug fix
- `docs:` - Update dokumentasi
- `style:` - Perubahan styling
- `refactor:` - Refactoring code
- `test:` - Tambah/update test
- `chore:` - Maintenance

Contoh:
```bash
git commit -m "feat: tambah export PDF untuk laporan sampah"
git commit -m "fix: perbaiki pagination tidak muncul"
git commit -m "docs: update README dengan panduan instalasi"
```

## 🌿 Branching Strategy (Opsional)

Untuk project yang lebih terorganisir:

### 1. Buat Branch untuk Fitur Baru

```bash
# Buat dan pindah ke branch baru
git checkout -b feature/nama-fitur

# Atau
git branch feature/nama-fitur
git checkout feature/nama-fitur
```

### 2. Kerja di Branch

```bash
# Add dan commit seperti biasa
git add .
git commit -m "feat: implementasi fitur baru"

# Push branch ke GitHub
git push origin feature/nama-fitur
```

### 3. Merge ke Main

Setelah fitur selesai:

```bash
# Pindah ke branch main
git checkout main

# Merge branch fitur
git merge feature/nama-fitur

# Push ke GitHub
git push origin main

# Hapus branch lokal (opsional)
git branch -d feature/nama-fitur

# Hapus branch di GitHub (opsional)
git push origin --delete feature/nama-fitur
```

## 🔒 Keamanan

### ⚠️ PENTING - Jangan Push File Sensitif!

File yang TIDAK BOLEH di-push:
- `.env` - Berisi password database
- Backup database dengan data real
- File dengan kredensial API
- Private keys
- Password atau token

### Jika Tidak Sengaja Push File Sensitif

1. **Hapus dari Git history:**
   ```bash
   git rm --cached .env
   git commit -m "remove sensitive file"
   git push origin main
   ```

2. **Ganti password/token** yang ter-expose

3. **Gunakan `.gitignore`** untuk prevent di masa depan

## 📊 Backup Database

### 1. Export Database (Tanpa Data Sensitif)

```bash
# Export struktur saja
mysqldump -u root -p --no-data eksperimen > database/schema.sql

# Export dengan sample data
mysqldump -u root -p eksperimen > database/eksperimen_backup.sql
```

### 2. Tambahkan ke Git

```bash
git add database/schema.sql
git commit -m "docs: tambah database schema"
git push origin main
```

⚠️ **JANGAN** push database dengan data user real!

## 🔍 Cek Repository di GitHub

Setelah push, buka:
```
https://github.com/username/uigm-polban
```

Pastikan:
- ✅ File-file penting ada
- ✅ `.env` TIDAK ada
- ✅ `vendor/` TIDAK ada
- ✅ README.md tampil dengan baik
- ✅ Struktur folder benar

## 🆘 Troubleshooting

### Error: "Permission denied (publickey)"

Gunakan HTTPS instead of SSH:
```bash
git remote set-url origin https://github.com/username/uigm-polban.git
```

### Error: "Repository not found"

Pastikan:
1. Repository sudah dibuat di GitHub
2. URL remote benar
3. Anda punya akses ke repository

Cek remote:
```bash
git remote -v
```

### Error: "Failed to push some refs"

Pull dulu sebelum push:
```bash
git pull origin main --rebase
git push origin main
```

### File Tidak Ter-ignore

Jika file yang seharusnya di-ignore masih ter-track:
```bash
# Remove from git tracking
git rm --cached nama-file

# Atau untuk folder
git rm -r --cached nama-folder/

# Commit
git commit -m "remove ignored files"
git push origin main
```

## 📱 GitHub Desktop (Alternatif GUI)

Jika tidak nyaman dengan command line, gunakan GitHub Desktop:

1. Download: https://desktop.github.com/
2. Install dan login
3. Add repository lokal
4. Commit dan push via GUI

## 🎯 Checklist Sebelum Push

- [ ] `.env` sudah di-ignore
- [ ] `vendor/` sudah di-ignore
- [ ] Tidak ada password/token di code
- [ ] README.md sudah update
- [ ] Commit message jelas
- [ ] Code sudah di-test
- [ ] Database backup (jika perlu)

## 📞 Bantuan

Jika ada masalah:
1. Cek dokumentasi Git: https://git-scm.com/doc
2. Cek GitHub Docs: https://docs.github.com
3. Tanya di GitHub Community: https://github.community

---

**Selamat! Project Anda sekarang sudah ter-backup di GitHub! 🎉**
