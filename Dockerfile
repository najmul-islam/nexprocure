# -------------------------
# Stage 1: Build PHP dependencies
# -------------------------
FROM php:8.2-fpm AS php-build

WORKDIR /var/www/html

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libonig-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql bcmath gd zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy app source
COPY . .

# Install PHP dependencies (only prod)
RUN composer install --no-dev --optimize-autoloader --no-scripts


# -------------------------
# Stage 2: Build frontend assets with Node/Vite
# -------------------------
FROM node:18-alpine AS node-build

WORKDIR /var/www/html

COPY --from=php-build /var/www/html /var/www/html

# Install Node dependencies & build
RUN npm install && npm run build


# -------------------------
# Stage 3: Final production container
# -------------------------
FROM nginx:alpine

# Install PHP-FPM and required extensions
RUN apk add --no-cache php82 php82-fpm php82-pdo_pgsql php82-pgsql supervisor bash

WORKDIR /var/www/html

# Copy Laravel app (PHP + vendor + built assets)
COPY --from=php-build /var/www/html /var/www/html
COPY --from=node-build /var/www/html/public/build /var/www/html/public/build

# Copy nginx config
COPY ./docker/nginx.conf /etc/nginx/conf.d/default.conf

# Permissions for Laravel
RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port for Render
EXPOSE 8080

# Start Supervisor to run both PHP-FPM and Nginx
CMD ["/bin/sh", "-c", "php-fpm82 -D && nginx -g 'daemon off;'"]
