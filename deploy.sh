#!/bin/bash
set -e # Script akan berhenti otomatis jika terjadi error

echo "⬇️ Mulai proses deployment..."

echo "-----------------------------------"
echo "🔄 Mengambil kode terbaru ke branch main..."
git fetch origin main
git reset --hard origin/main
git clean -df

echo "-----------------------------------"
echo "📦 Menginstal/update dependencies PHP via Composer (Mengabaikan Cek Versi PHP)..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --ignore-platform-reqs

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
echo "🔐 Merapihkan hak akses file (Permissions)..."
chown -R sirupi:sirupi /home/sirupi/public_html/audira.site
chmod -R 775 /home/sirupi/public_html/audira.site/storage
chmod -R 775 /home/sirupi/public_html/audira.site/bootstrap/cache

echo "-----------------------------------"
echo "✅ DEPLOYMENT SUKSES!"
