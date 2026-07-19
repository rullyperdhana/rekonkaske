#!/bin/bash

echo "==============================================="
echo "   Menarik Pembaruan SiReKe dari GitHub"
echo "==============================================="

echo "1. Menarik paksa pembaruan terbaru dari GitHub..."
git fetch --all
git reset --hard origin/main

echo "2. Memperbarui Pustaka/Dependencies (jika ada)..."
/www/server/php/84/bin/php /usr/bin/composer install --optimize-autoloader --no-dev

echo "3. Menjalankan Migrasi Database..."
/www/server/php/84/bin/php artisan migrate --force

echo "4. Mengoptimalkan Kinerja (Cache & Routes)..."
/www/server/php/84/bin/php artisan optimize

echo "5. Mengamankan Hak Akses Server (aaPanel)..."
chown -R www:www /www/wwwroot/sireke.cloud
chmod -R 775 /www/wwwroot/sireke.cloud/storage /www/wwwroot/sireke.cloud/bootstrap/cache

echo "==============================================="
echo "   Selesai! Website Anda sekarang up-to-date."
echo "==============================================="
