# Etapa 1: build de dependencias
FROM composer:latest AS vendor

WORKDIR /app
COPY backend/composer.json backend/composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-interaction

# Etapa 2: Laravel + PHP
FROM php:8.2-fpm

WORKDIR /var/www

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libzip-dev zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip

# Copiar Composer desde el build anterior
COPY --from=vendor /app/vendor /var/www/vendor

# Copiar el resto del proyecto
COPY backend ./

# Permisos y key de la app
RUN chmod -R 777 storage bootstrap/cache \
    && php artisan config:cache \
    && php artisan route:cache

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000

