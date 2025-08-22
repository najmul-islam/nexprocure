# Base image
FROM richarvey/nginx-php-fpm:3.1.6

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Laravel environment config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Nginx & PHP-FPM config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Create SQLite database file (if it doesn't exist) and set permissions
RUN touch database/database.sqlite \
    && chmod -R 777 database storage bootstrap/cache

# Install Composer dependencies (optional - depends on your deploy flow)
# Uncomment the line below if you want Composer install here:
# RUN composer install --no-dev --optimize-autoloader

# Start container
CMD ["/start.sh"]
