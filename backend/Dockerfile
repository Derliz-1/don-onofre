# Fase 1: Construcción (Composer Install)
FROM composer:2 AS builder

WORKDIR /app

COPY backend/composer.json backend/composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress --no-interaction

# Fase 2: Producción (Servidor PHP)
FROM php:8.2-fpm-alpine

# Instalar extensiones PHP necesarias
RUN apk --no-cache add \
    php-pdo php-pdo_mysql php-pdo_sqlite php-mbstring php-exif php-pcntl php-bcmath php-gd php-tokenizer php-ctype php-curl php-session php-openssl \
    oniguruma-dev libpng-dev libjpeg-turbo-dev freetype-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Configurar directorio de trabajo
WORKDIR /var/www

# Copiar proyecto (sin vendor todavía)
COPY backend/ .

# Copiar vendor generado en la etapa anterior
COPY --from=builder /app/vendor ./vendor

# Crear base de datos si no existe
RUN mkdir -p database && touch database/database.sqlite

# Permisos correctos
RUN chmod -R 755 /var/www && chown -R www-data:www-data /var/www

# Exponer el puerto
EXPOSE 8000

# Comando para levantar el servidor
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
