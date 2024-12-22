# Use the official PHP 8.1 FPM slim version
FROM php:8.1-fpm-alpine

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
    autoconf \
    gcc \
    g++ \
    make \
    linux-headers

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \
        pdo \
        pdo_mysql \
        zip \
        bcmath \
        mbstring \
        opcache

# Configure PHP memory limits
RUN echo "memory_limit=-1" > /usr/local/etc/php/conf.d/memory-limit.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy composer files first to leverage Docker cache
COPY composer.json composer.lock ./

# Create empty composer scripts for now
RUN echo "{}" > composer.json.tmp && \
    mv composer.json composer.json.bak && \
    mv composer.json.tmp composer.json

# Install dependencies with increased verbosity for debugging
RUN composer install --no-scripts --no-autoloader --no-interaction --prefer-dist -vvv || \
    (echo "Composer install failed. Showing directory contents:" && \
     ls -la && \
     cat composer.json && \
     exit 1)

# Restore original composer.json
RUN mv composer.json.bak composer.json

# Copy the rest of the application code
COPY . .

# Generate optimized autoload files
RUN composer dump-autoload --optimize

# Set up permissions
RUN chown -R www-data:www-data /var/www && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose port 9000 and start PHP-FPM server
EXPOSE 9000
CMD ["php-fpm"]