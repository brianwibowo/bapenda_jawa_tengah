# --------------------------------------------------------
# 🐳 Dockerfile for Laravel 12 on Render (PHP 8.3 + Apache)
# --------------------------------------------------------

# Gunakan PHP 8.3 agar cocok dengan Laravel 12 dan semua dependency
FROM php:8.3-apache

# Install ekstensi dan dependency sistem yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer global
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy semua file dari proyek ke container
COPY . .

# Install dependency Laravel
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Set permission folder penting
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port default Apache
EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]
