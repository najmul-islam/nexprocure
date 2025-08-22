FROM php:8.2-fpm

WORKDIR /var/www/html

# Install system dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip sqlite3 libsqlite3-dev \
    libonig-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    nginx \
    && docker-php-ext-install pdo pdo_sqlite mbstring zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy application
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies without running artisan scripts (safer)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Make storage and cache writable
RUN mkdir -p storage/framework/sessions storage/framework/cache storage/framework/views bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Copy Nginx config
COPY ./docker/nginx.conf /etc/nginx/conf.d/default.conf

# Expose port 80
EXPOSE 80

# Run PHP-FPM and Nginx
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
