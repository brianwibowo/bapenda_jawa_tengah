#!/bin/bash
# ==================================================
# Initial Deployment Script for audira.site
# Run this ONCE on the VPS for first-time setup
# ==================================================

set -e

PROJECT_DIR="/var/www/audira.site"
REPO_URL="https://github.com/brianwibowo/bapenda_jawa_tengah.git"

echo "=========================================="
echo "🚀 Initial Setup — Bapenda Jateng"
echo "=========================================="

# 1. Clone repository
if [ ! -d "$PROJECT_DIR/.git" ]; then
    echo "📥 Cloning repository..."
    mkdir -p "$PROJECT_DIR"
    git clone "$REPO_URL" "$PROJECT_DIR"
else
    echo "📂 Project directory already exists, pulling latest..."
    cd "$PROJECT_DIR"
    git pull origin main
fi

cd "$PROJECT_DIR"

# 2. Create .env from example if it doesn't exist
if [ ! -f ".env" ]; then
    echo "📝 Creating .env file from .env.example..."
    cp .env.example .env
    echo ""
    echo "✅ .env created with pre-filled values."
    echo "   Review if needed: nano $PROJECT_DIR/.env"
    echo ""
fi

# 3. Build and start containers
echo "🔨 Building Docker images..."
docker compose build

echo "🚀 Starting containers..."
docker compose up -d

# 4. Wait for database to be healthy
echo "⏳ Waiting for database to be ready..."
sleep 20

# 5. Run migrations
echo "📊 Running database migrations..."
docker compose exec -T app php artisan migrate --force

# 6. Run seeders (only on initial setup!)
read -p "🌱 Run database seeders? (y/N): " run_seeders
if [ "$run_seeders" = "y" ] || [ "$run_seeders" = "Y" ]; then
    docker compose exec -T app php artisan db:seed --force
fi

# 7. Create storage link
echo "🔗 Creating storage symlink..."
docker compose exec -T app php artisan storage:link || true

# 8. Cache configuration
echo "⚙️  Caching configuration..."
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose exec -T app php artisan event:cache

echo "=========================================="
echo "✅ Initial deployment complete!"
echo "🌐 Visit https://audira.site"
echo "=========================================="
echo ""
echo "📋 Useful commands:"
echo "   docker compose ps          — Check container status"
echo "   docker compose logs -f     — View logs"
echo "   docker compose exec app sh — Shell into app container"
echo "   docker compose down        — Stop all containers"
echo "=========================================="
