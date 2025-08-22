# -------------------------
# Stage 1: Build stage (PHP + Node + Composer)
# -------------------------
FROM php:8.2-fpm AS build

WORKDIR /var/www/html

# Install system dependencies + PHP extensions BEFORE composer
RUN apt-get update && apt-get install -y \
    git curl zip unzip sqlite3 libsqlite3-dev \
    libonig-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev pkg-config \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_sqlite mbstring exif pcntl bcmath zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Ensure PHP CLI sees the extensions
RUN php -m | grep -E 'gd|pdo_sqlite|mbstring'

# Install Node.js (for Vite + Tailwind)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer files and install PHP dependencies
COPY composer.json composer.lock ./
# Use --ignore-platform-reqs to bypass ext-gd detection issue in build stage
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Copy full application code
COPY . .

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache database

# Build Tailwind + Vite assets
RUN npm install
RUN npm run build
