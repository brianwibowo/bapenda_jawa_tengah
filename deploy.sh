#!/bin/bash
set -e # Script akan berhenti otomatis jika terjadi error

echo "⬇️ Mulai proses deployment..."

echo "-----------------------------------"
echo "🔄 Mengambil kode terbaru ke branch main..."
git pull origin main

echo "-----------------------------------"
echo "📦 Menginstal/update dependencies PHP via Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "-----------------------------------"
echo "🗄️ Menjalankan database migration..."
php artisan migrate --force

echo "-----------------------------------"
echo "🚀 Membersihkan & caching konfigurasi Laravel..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Hapus tanda pagar (#) di bawah ini jika project-mu menggunakan NPM/Vite
# echo "-----------------------------------"
# echo "🌐 Build File CSS & JS NPM..."
# npm install
# npm run build

echo "-----------------------------------"
echo "✅ DEPLOYMENT SUKSES!"
