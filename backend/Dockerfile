# Usa PHP 8.2 oficial con Alpine (liviano)
FROM php:8.2-fpm-alpine

# Instala extensiones PHP necesarias
RUN apk add --no-cache \
    php-pdo php-pdo_mysql php-pdo_sqlite php-mbstring php-exif php-pcntl php-bcmath php-gd php-tokenizer php-ctype php-curl php-session php-openssl \
    php-fileinfo php-zip php-simplexml php-dom php-xmlwriter php-xmlreader \
    php-json php-iconv php-opcache \
    oniguruma-dev libpng-dev libjpeg-turbo-dev freetype-dev zlib-dev zip unzip curl git

# Copia el proyecto (como estás en carpeta backend/ ya)
WORKDIR /var/www

COPY . .

# Instala Composer (desde la imagen oficial)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instala dependencias de Laravel
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction

# Crea el archivo SQLite si no existe
RUN mkdir -p database && touch database/database.sqlite

# Permisos correctos
RUN chmod -R 775 storage bootstrap/cache database && \
    chown -R www-data:www-data /var/www

# Puerto que expone Laravel
EXPOSE 8000

# Comando para correr el servidor de Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
