#!/bin/sh
set -e

# Sync built public files to the shared volume (for nginx)
echo "📂 Syncing public files to shared volume..."
cp -a /app-public-cache/. /var/www/html/public/

# Fix permissions
chown -R www-data:www-data /var/www/html/public
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

echo "✅ Public files synced. Starting PHP-FPM..."
exec "$@"
