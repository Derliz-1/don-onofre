# Usa imagen oficial PHP + Apache
FROM php:8.2-apache

# Instala extensiones necesarias
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

# Configurar e instalar extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite mbstring zip

# Copiamos configuración apache
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Copiamos todo el proyecto
COPY . /var/www/html

# Permisos correctos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Activar módulo rewrite
RUN a2enmod rewrite

# Exponemos el puerto
EXPOSE 80

# NUEVO: Comando de inicio corregido (migra + arranca apache)
CMD php artisan migrate --force && apache2-foreground