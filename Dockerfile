FROM php:8.2-fpm

WORKDIR /var/www/html

# Install system dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip sqlite3 libsqlite3-dev \
    libonig-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    nginx supervisor \
    && docker-php-ext-install pdo pdo_sqlite mbstring zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy application
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Create SQLite database file
RUN mkdir -p database && touch database/database.sqlite \
    && chmod -R 777 database storage bootstrap/cache public

# Configure Nginx
RUN rm /etc/nginx/sites-enabled/default
COPY ./docker/nginx.conf /etc/nginx/conf.d/default.conf

# Configure Supervisor
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
