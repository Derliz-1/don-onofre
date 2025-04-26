# Etapa 1: Construcción de dependencias
FROM composer:latest AS vendor

WORKDIR /app
COPY backend/composer.json backend/composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-interaction

# Etapa 2: Aplicación Laravel
FROM php:8.2-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libzip-dev zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip \
    && a2enmod rewrite

# Configuración de Apache
COPY backend/.docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Copiar aplicación
COPY backend /var/www/html
COPY --from=vendor /app/vendor /var/www/html/vendor

WORKDIR /var/www/html

# Crear archivo SQLite si no existe
RUN touch database/database.sqlite

# Asignar permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Variables de entorno necesarias
ENV APP_ENV=production
ENV APP_KEY=base64:GENERAR_O_REEMPLAZAR
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/var/www/html/database/database.sqlite

# Ejecutar comandos de Laravel
RUN php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear && \
    php artisan migrate --force

EXPOSE 80
