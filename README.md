FROM php:8.4-fpm-alpine

# Install system dependencies & build tools
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    linux-headers \
    $PHPIZE_DEPS \
    openssl-dev

# Install PHP extensions (sockets for AMQP, pcntl for worker signal handling, bcmath)
RUN docker-php-ext-install \
    pdo \
    bcmath \
    sockets \
    pcntl

# Install MongoDB extension via PECL
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files first for layer caching
COPY composer.json composer.lock* ./

# Install application dependencies (omit dev in container build if needed, keep standard here)
RUN composer install --no-interaction --no-scripts --no-autoloader --prefer-dist

# Copy application source code
COPY . .

# Generate optimized autoload files
RUN composer dump-autoload --optimize

# Set proper permissions for Laravel writable directories
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]