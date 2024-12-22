# Use PHP 8.2 instead of 8.1
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk update && apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    git \
    unzip \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    linux-headers \
    postgresql-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \
        pdo \
        pdo_mysql \
        zip \
        bcmath \
        mbstring \
        opcache \
        intl \
        pcntl

# Install PECL extensions
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis

# Configure PHP
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/docker-php-memory-limit.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-scripts --no-autoloader --no-interaction --prefer-dist

# Copy the rest of the application code
COPY . .

# Generate optimized autoload files and run scripts
RUN composer dump-autoload --optimize

# Set up permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose port 9000 and start PHP-FPM server
EXPOSE 9000
CMD ["php-fpm"]