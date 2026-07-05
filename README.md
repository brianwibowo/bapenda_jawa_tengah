# Ditlantas Jawa Tengah — Hapus Regident

Sistem Informasi Penghapusan Registrasi dan Identifikasi Kendaraan Bermotor (Hapus Regident) untuk Direktorat Lalu Lintas (Ditlantas) Provinsi Jawa Tengah.

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.4)
- **Frontend:** TailwindCSS, Alpine.js, Bootstrap 5, Vite
- **Database:** MySQL 8.0
- **PDF Engine:** Barryvdh DomPDF
- **Media:** Spatie Media Library
- **Auth & RBAC:** Laravel Breeze + Spatie Laravel Permission
- **Container:** Docker (PHP-FPM 8.4, Nginx, MySQL 8.0)

---

## Prerequisites

| Mode | Requirements |
|------|-------------|
| **Manual** | PHP ^8.2, Composer 2.x, Node.js 20, MySQL 8.0 |
| **Docker** | Docker Engine + Docker Compose |

---

## Quick Start (Docker)

```bash
# 1. Clone & enter project
git clone <repo-url> bapenda_jawa_tengah
cd bapenda_jawa_tengah

# 2. Configure environment
cp .env.example .env
# Edit .env if needed (defaults work out of the box)

# 3. Build & start containers
docker compose up -d --build

# 4. Run migrations & seeders
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan db:seed --force

# 5. Cache config (production)
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose exec -T app php artisan event:cache
```

App → `http://localhost:8085`

---

## Manual Setup (Local Development)

```bash
# 1. Install dependencies
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate
# Set DB_* in .env to match your MySQL database

# 3. Database
php artisan migrate
php artisan db:seed

# 4. Storage link
php artisan storage:link

# 5. Build frontend
npm run build

# 6. Start dev server
php artisan serve --host=0.0.0.0 --port=8085
# Or run all services concurrently:
composer dev
```

App → `http://localhost:8085`

---

## Docker Architecture

```
┌─────────────┐      ┌──────────────┐      ┌──────────────┐
│   nginx     │─────▶│  php-fpm     │─────▶│   mysql      │
│ :8085 → 80  │      │  :9000       │      │  :3306       │
│ alpine      │      │  php:8.4     │      │  mysql:8.0   │
└─────────────┘      └──────────────┘      └──────────────┘
                           │
                     ┌─────┴──────┐
                     │  Volumes   │
                     │  storage/  │
                     │  public/   │
                     └────────────┘
```

### Services

| Service | Container Name | Image | Port |
|---------|---------------|-------|------|
| **app** | bapenda-app | `php:8.4-fpm` (custom) | 9000 |
| **db** | bapenda-db | `mysql:8.0.40` | 3306 (internal) |
| **nginx** | bapenda-nginx | `nginx:1.26-alpine` | 8085 → 80 |

### Docker Compose Commands

```bash
# Build all images
docker compose build

# Start all services
docker compose up -d

# View logs
docker compose logs -f

# Execute commands in the app container
docker compose exec app php artisan migrate
docker compose exec app sh

# Restart a service
docker compose restart nginx

# Rebuild and recreate a single service
docker compose up -d --build app

# Stop all
docker compose down

# Reset database (⚠️ deletes all data)
docker compose down db
docker volume rm bapenda_jawa_tengah_db-data
docker compose up -d db
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan db:seed --force
```

---

## Deployment (VPS)

Use the provided scripts for first-time setup and subsequent deployments:

### First-time setup
```bash
bash deploy-init.sh
```

### Subsequent deployments
```bash
bash deploy.sh
```

Or manually:
```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
chmod -R 775 storage bootstrap/cache
```

---

## Configuration

### Environment Variables (`.env`)

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_ENV` | `production` | `local` for development |
| `APP_DEBUG` | `false` | `true` for debugging |
| `APP_URL` | `https://audira.site` | Application base URL |
| `DB_HOST` | `db` | `127.0.0.1` for local, `db` for Docker |
| `DB_DATABASE` | `bapenda_new` | MySQL database name |
| `DB_USERNAME` | `root` | MySQL user |
| `DB_PASSWORD` | `passwordBaru123` | MySQL password |
| `SESSION_DRIVER` | `database` | Session storage driver |
| `CACHE_STORE` | `database` | Cache driver |

### Nginx Config (`docker/nginx/default.conf`)
- Laravel URL rewriting at `/`
- PHP-FPM upstream at `app:9000`
- Static files cached for 30 days
- Max upload size: 50 MB

### PHP-FPM Config (`docker/php/www.conf`)
- Pool: `www` with `www-data` user
- Dynamic process management (max 3 children)
- `clear_env = no` — passes Docker environment to PHP workers

### PHP Config (`docker/php/php.ini`)
- Upload max: 50 MB
- Memory limit: 128 MB
- Timezone: Asia/Jakarta

### MySQL Config (`docker/mysql/my.cnf`)
- Optimized for low-memory VPS (1 GB RAM)
- `performance_schema=OFF`
- `innodb_buffer_pool_size=64M`
- `max_connections=10`

### OPcache (`docker/php/opcache.ini`)
- Enabled with 128 MB memory
- Timestamp validation disabled (production)

---

## Test Accounts

| Role | Email | Password |
|------|-------|----------|
| **Superadmin** | superadmin@bapenda.com | Password |
| **Warga** | jil@bapenda.com | jihtar-Hutkyp-vokfo8 |

---

## Project Structure

```
bapenda_jawa_tengah/
├── app/                    # Laravel application code
│   ├── Http/Controllers/
│   ├── Models/
│   └── ...
├── bootstrap/
├── config/                 # Laravel configuration
├── database/
│   ├── migrations/
│   └── seeders/
├── docker/
│   ├── entrypoint.sh       # Docker entrypoint
│   ├── mysql/my.cnf        # MySQL tuned config
│   ├── nginx/default.conf  # Nginx site config
│   ├── php/
│   │   ├── opcache.ini
│   │   ├── php.ini
│   │   └── www.conf        # PHP-FPM pool config
│   └── php.ini
├── docker-compose.yml      # Orchestration
├── Dockerfile              # Multi-stage build
├── resources/              # Views, assets, lang
├── routes/                 # Route definitions
├── storage/                # Logs, cache, uploads
├── public/                 # Document root
├── deploy.sh               # Deployment script
└── deploy-init.sh          # First-time setup
```

---

## Troubleshooting

### PHP-FPM fails to start
Ensure `docker/php/www.conf` has `user` and `group` directives:
```ini
[www]
user = www-data
group = www-data
clear_env = no
```

### Database connection refused (Docker)
Make sure `.env` has `DB_HOST=db` (Docker service name), not `127.0.0.1`.

### Nginx 502 Bad Gateway
Restart nginx to clear cached DNS:
```bash
docker compose restart nginx
```

### Reset everything
```bash
docker compose down -v
docker compose up -d --build
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan db:seed --force
```

### View Laravel logs
```bash
docker compose exec app tail -f /var/www/html/storage/logs/laravel.log
```

---

## More Info

- [Deskripsi Aplikasi](DESKRIPSI_APLIKASI.md) — detailed app description & workflow
- [Manual Aplikasi](MANUAL_APLIKASI.md) — user manual for all roles
- [Bug Fix Summary](BUG_FIX_SUMMARY.md) — known issues & fixes
