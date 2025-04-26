FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libzip-dev zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction

# Crear archivo de base de datos SQLite
RUN mkdir -p database && touch database/database.sqlite

# Asignar permisos adecuados
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 8000

# Comando por defecto
CMD php artisan serve --host=0.0.0.0 --port=8000

