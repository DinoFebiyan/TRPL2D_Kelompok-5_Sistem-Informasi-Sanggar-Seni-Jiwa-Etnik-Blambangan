# =============================================================
#  Jiwa Etnik Blambangan — Multi-stage Dockerfile
#
#  Stages:
#    base        → PHP 8.2-FPM + all required extensions
#    dev         → base (code mounted at runtime, no baking)
#    vendor      → Composer install (intermediate)
#    assets      → npm run build (intermediate)
#    production  → Final image with vendor + built assets baked in
#
#  Usage:
#    Dev:        docker compose up            (uses 'dev' target)
#    Staging:    docker compose -f docker-compose.staging.yml up
#    Production: docker compose -f docker-compose.prod.yml up
# =============================================================

# -------------------------------------------------------------
# Stage 1: base — PHP-FPM + system dependencies + extensions
# -------------------------------------------------------------
FROM php:8.2-fpm-alpine AS base

LABEL maintainer="JEB Team"

# Install system dependencies
RUN apk add --no-cache \
    bash \
    curl \
    freetype-dev \
    git \
    icu-dev \
    jpeg-dev \
    libpng-dev \
    libwebp-dev \
    libzip-dev \
    oniguruma-dev \
    unzip \
    zip

# Install PHP extensions
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        ctype \
        exif \
        fileinfo \
        gd \
        intl \
        mbstring \
        opcache \
        pdo \
        pdo_mysql \
        zip

# Install Composer globally
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# -------------------------------------------------------------
# Stage 2: dev — Development image (source mounted at runtime)
# -------------------------------------------------------------
FROM base AS dev

# Copy PHP dev configuration
COPY docker/php/php.ini $PHP_INI_DIR/conf.d/99-custom.ini

# Copy and register entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]

# -------------------------------------------------------------
# Stage 3: vendor — Install PHP dependencies (no-dev)
# -------------------------------------------------------------
FROM composer:2.8 AS vendor

WORKDIR /app

# Copy only dependency manifests first for layer caching
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --ignore-platform-reqs

# Copy the rest and dump optimized autoloader
COPY . .
RUN composer dump-autoload \
    --optimize \
    --no-dev \
    --no-interaction

# -------------------------------------------------------------
# Stage 4: assets — Build frontend assets (CSS/JS via Vite)
# -------------------------------------------------------------
FROM node:20-alpine AS assets

WORKDIR /app

# Copy dependency manifests first for layer caching
COPY package.json package-lock.json ./
RUN npm ci --frozen-lockfile

# Copy application source
COPY . .

# Build production assets
RUN npm run build

# -------------------------------------------------------------
# Stage 5: production — Final deployable image
# -------------------------------------------------------------
FROM base AS production

# Copy PHP production configuration (opcache enabled, debug off)
COPY docker/php/php-prod.ini $PHP_INI_DIR/conf.d/99-custom.ini

# Copy PHP vendor from vendor stage
COPY --from=vendor /app/vendor ./vendor

# Copy built frontend assets from assets stage
COPY --from=assets /app/public/build ./public/build

# Copy application source code
COPY . .

# Set correct ownership and permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copy and register entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
