#!/bin/bash
# Script helper untuk push ke GitHub - Linux/Mac
# Cara pakai: ./git-push.sh "pesan commit"

echo "========================================"
echo "  UIGM POLBAN - Git Push Helper"
echo "========================================"
echo ""

# Cek apakah ada pesan commit
if [ -z "$1" ]; then
    echo "ERROR: Pesan commit harus diisi!"
    echo "Contoh: ./git-push.sh \"feat: tambah fitur baru\""
    echo ""
    exit 1
fi

echo "[1/4] Checking status..."
git status
echo ""

echo "[2/4] Adding files..."
git add .
echo ""

echo "[3/4] Committing with message: $1"
git commit -m "$1"
echo ""

echo "[4/4] Pushing to GitHub..."
git push origin main
echo ""

if [ $? -eq 0 ]; then
    echo "========================================"
    echo "  SUCCESS! Push ke GitHub berhasil!"
    echo "========================================"
else
    echo "========================================"
    echo "  ERROR! Push ke GitHub gagal!"
    echo "  Cek error message di atas."
    echo "========================================"
fi

echo ""
