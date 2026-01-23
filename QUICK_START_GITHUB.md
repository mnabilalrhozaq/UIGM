# 🚀 Quick Start - Push ke GitHub

Panduan cepat untuk push project ke GitHub dalam 5 menit.

## ✅ Checklist Persiapan

- [ ] Git sudah terinstall (`git --version`)
- [ ] Punya akun GitHub
- [ ] File `.env` sudah di-ignore (jangan sampai ke-push!)

## 📝 Langkah Cepat

### 1. Inisialisasi Git (Jika Belum)

```bash
git init
```

### 2. Add Semua File

```bash
git add .
```

### 3. Commit Pertama

```bash
git commit -m "Initial commit: UIGM POLBAN System"
```

### 4. Buat Repository di GitHub

1. Buka https://github.com/new
2. Nama repository: `uigm-polban`
3. Pilih **Private** atau **Public**
4. **JANGAN** centang "Initialize with README"
5. Klik **Create repository**

### 5. Connect ke GitHub

Ganti `username` dengan username GitHub Anda:

```bash
git remote add origin https://github.com/username/uigm-polban.git
git branch -M main
git push -u origin main
```

### 6. Login

Saat diminta:
- **Username**: username GitHub Anda
- **Password**: gunakan **Personal Access Token** (bukan password)

#### Cara Buat Token:
1. GitHub → Settings → Developer settings
2. Personal access tokens → Tokens (classic)
3. Generate new token → centang **repo**
4. Copy token dan simpan!

## 🎯 Selesai!

Repository Anda sekarang sudah di GitHub:
```
https://github.com/username/uigm-polban
```

## 🔄 Update Selanjutnya

Setiap kali ada perubahan:

```bash
git add .
git commit -m "deskripsi perubahan"
git push origin main
```

### Atau Gunakan Script Helper:

**Windows:**
```bash
git-push.bat "deskripsi perubahan"
```

**Linux/Mac:**
```bash
chmod +x git-push.sh
./git-push.sh "deskripsi perubahan"
```

## ⚠️ PENTING!

File yang TIDAK BOLEH di-push:
- ❌ `.env` (password database!)
- ❌ `vendor/` (install via composer)
- ❌ `writable/logs/*` (log files)
- ❌ Database backup dengan data real

File `.gitignore` sudah mengatur ini, tapi tetap cek dengan:
```bash
git status
```

## 🆘 Troubleshooting

### Error: "Permission denied"
Gunakan Personal Access Token, bukan password akun.

### Error: "Repository not found"
Pastikan URL remote benar:
```bash
git remote -v
```

### File `.env` Ter-push?
Hapus segera:
```bash
git rm --cached .env
git commit -m "remove .env"
git push origin main
```
Lalu **GANTI PASSWORD DATABASE!**

## 📚 Dokumentasi Lengkap

Lihat `SETUP_GITHUB.md` untuk panduan detail.

---

**Happy Coding! 🎉**
