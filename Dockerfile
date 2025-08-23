# Base PHP image
FROM php:8.2-fpm

WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip sqlite3 libsqlite3-dev \
    libonig-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    nginx nodejs npm \
    && docker-php-ext-install pdo pdo_sqlite mbstring zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy app
COPY . .

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Node build
RUN npm install && npm run build

# Fix permissions
RUN mkdir -p storage/framework/{sessions,cache,views} bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Copy Nginx config & remove default site
COPY ./docker/nginx.conf /etc/nginx/conf.d/default.conf
RUN rm -f /etc/nginx/sites-enabled/default

# Expose port 80
EXPOSE 80

# Start app
CMD ["sh", "-c", "\
    mkdir -p database && touch database/database.sqlite && chmod -R 777 database storage bootstrap/cache && \
    php artisan migrate --force && \
    php-fpm -D && \
    nginx -g 'daemon off;' \
"]
