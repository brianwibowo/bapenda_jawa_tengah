# --------------------------------------------------------
# 🐳 Dockerfile for Laravel 12 (Render + PHP 8.3 + Apache)
# --------------------------------------------------------

FROM php:8.3-apache

# Install dependency sistem
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy semua file project
COPY . .

# Install dependency Laravel
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Set permission folder penting
RUN chown -R www-data:www-data storage bootstrap/cache

# 🔧 Ubah DocumentRoot ke folder public Laravel
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Aktifkan mod_rewrite untuk route Laravel
RUN a2enmod rewrite

# Tambahkan aturan rewrite agar route Laravel berfungsi
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
</Directory>' >> /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]
