# Use the official PHP 8.1 FPM slim version (lighter than full PHP image)
FROM php:8.1-fpm-alpine

# Install system dependencies using Alpine package manager (apk)
RUN apk update && apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    libfreetype-dev \
    zip \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory in the container
WORKDIR /var/www

# Copy the Laravel app code into the container
COPY . .

# Install PHP dependencies (Composer)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set up permissions for Laravel
RUN chown -R www-data:www-data /var/www && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose port 9000 and start PHP-FPM server
EXPOSE 9000
CMD ["php-fpm"]
