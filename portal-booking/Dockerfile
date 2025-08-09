# Stage 1: Composer Dependencies
FROM composer:latest AS builder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts
COPY . .

# Stage 2: Final PHP Image
FROM php:8.2-fpm-alpine
WORKDIR /var/www/html

# Install dependencies
RUN apk update && apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libzip-dev \
    zip \
    unzip \
    bash

# Configure and install gd
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Install other PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath zip

# salin composer excuteable agar bisa digunakan
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy built app from builder stage
COPY --from=builder /app ./
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
