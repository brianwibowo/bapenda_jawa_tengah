# ============================================
# Stage 1: Build frontend assets with Vite
# ============================================
FROM node:20.18-alpine3.20 AS node-builder

WORKDIR /build

# Copy package files first for better layer caching
COPY package.json package-lock.json* ./
RUN npm ci --ignore-scripts

# Copy source files needed for Vite build
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources/ resources/

RUN npm run build

# ============================================
# Stage 2: Install PHP dependencies
# ============================================
FROM composer:2.8 AS composer-builder

WORKDIR /build

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction

COPY . .
RUN composer dump-autoload --optimize --no-dev

# ============================================
# Stage 3: Production PHP-FPM image
# ============================================
FROM php:8.2-fpm-bookworm AS production

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    unzip \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copy OPcache configuration
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html

# Copy application from composer stage
COPY --from=composer-builder /build /var/www/html

# Copy built frontend assets from node stage
COPY --from=node-builder /build/public/build /var/www/html/public/build

# Create required directories and set permissions
RUN mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Switch to non-root user
USER www-data

EXPOSE 9000

CMD ["php-fpm"]
