#!/bin/bash

echo "====================================="
echo "   Mendorong Kode ke GitHub"
echo "====================================="

# Menambahkan semua file yang berubah
echo "1. Menambahkan file ke staging area..."
git add .

# Membuat pesan commit otomatis berdasarkan waktu saat ini
COMMIT_MSG="Update otomatis: $(date +'%Y-%m-%d %H:%M:%S')"
echo "2. Membuat commit: $COMMIT_MSG"
git commit -m "$COMMIT_MSG"

# Mendorong (push) ke GitHub branch main
echo "3. Mendorong ke GitHub (origin main)..."
git push origin main

echo "====================================="
echo "   Selesai!"
echo "====================================="
