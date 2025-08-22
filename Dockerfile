# Base PHP image
FROM php:8.2-fpm

WORKDIR /var/www/html

# Install system dependencies + PHP extensions + Node.js for Vite
RUN apt-get update && apt-get install -y \
    git curl zip unzip sqlite3 libsqlite3-dev \
    libonig-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    nginx nodejs npm \
    && docker-php-ext-install pdo pdo_sqlite mbstring zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy application files
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies without scripts (safer for Docker)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Make storage and cache writable
RUN mkdir -p storage/framework/sessions storage/framework/cache storage/framework/views bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Install Node dependencies and build Vite assets
RUN npm install && npm run build

# Copy Nginx config
COPY ./docker/nginx.conf /etc/nginx/conf.d/default.conf
RUN rm -rf /usr/share/nginx/html/*
# Expose port 80
EXPOSE 80

# Start Laravel app (create SQLite, run migrations, start PHP-FPM + Nginx)
CMD ["sh", "-c", "\
    mkdir -p database && touch database/database.sqlite && chmod -R 777 database storage bootstrap/cache && \
    php artisan migrate --force && \
    php-fpm -D && \
    nginx -g 'daemon off;' \
"]

