FROM php:8.3-cli
# Force update trigger
# Install system dependencies & PHP extensions including GD
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Install dependencies as superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --optimize-autoloader --no-interaction --ignore-platform-req=ext-gd

# Ensure storage and bootstrap/cache are writable
RUN chmod -R 777 storage bootstrap/cache

# Expose port and start application
EXPOSE 8080
CMD php artisan config:clear && php artisan cache:clear && php -S 0.0.0.0:${PORT:-8080} -t public
