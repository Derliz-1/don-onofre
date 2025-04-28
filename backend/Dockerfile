# Etapa de construcción
FROM php:8.2-apache as builder

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libsqlite3-dev \
    libonig-dev \
    zip \
    unzip \
    git \
    curl

# Configurar GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Instalar extensiones de PHP
RUN docker-php-ext-install gd pdo pdo_mysql pdo_sqlite mbstring zip

# Copiar archivos del proyecto
COPY . /var/www/html

# Ir a carpeta del proyecto
WORKDIR /var/www/html

# Instalar dependencias de PHP (composer install)
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    composer install --no-dev --optimize-autoloader --no-interaction

# Crear carpeta de base de datos SQLite
RUN mkdir -p /var/www/database && touch /var/www/database/database.sqlite

# Dar permisos a Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/database

# Activar mod_rewrite de Apache
RUN a2enmod rewrite

# Migrar base de datos automáticamente
RUN php artisan migrate --force

# Configurar permisos correctos otra vez
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]
