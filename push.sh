#!/bin/bash

echo "====================================="
echo "   Mendorong Kode ke GitHub"
echo "====================================="

# Menambahkan semua file yang berubah
echo "1. Menambahkan file ke staging area..."
git add .

# Meminta input dari pengguna untuk pesan commit
echo -n "2. Ketikkan deskripsi update Anda (contoh: 'Menambahkan fitur maintenance'): "
read COMMIT_MSG

# Jika pengguna hanya menekan enter (kosong), gunakan waktu otomatis
if [ -z "$COMMIT_MSG" ]; then
    COMMIT_MSG="Update otomatis: $(date +'%Y-%m-%d %H:%M:%S')"
fi

echo "Membuat commit: $COMMIT_MSG"
git commit -m "$COMMIT_MSG"

# Mendorong (push) ke GitHub branch main
echo "3. Mendorong ke GitHub (origin main)..."
git push origin main

echo "====================================="
echo "   Selesai!"
echo "====================================="
