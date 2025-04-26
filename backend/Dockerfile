# Etapa 1: Dependencias con Composer
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress --no-interaction

# Etapa 2: Servidor PHP con Laravel
FROM php:8.2-fpm-alpine

# Instalar extensiones necesarias
RUN apk add --no-cache \
    bash libpng-dev libjpeg-turbo-dev freetype-dev oniguruma-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

WORKDIR /var/www

# Copiar todos los archivos desde la carpeta backend/
COPY . .

# Copiar vendor desde la etapa anterior
COPY --from=vendor /app/vendor ./vendor

# Asegurar base de datos SQLite
RUN mkdir -p database && touch database/database.sqlite

# Permisos
RUN chmod -R 755 /var/www && chown -R www-data:www-data /var/www

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
