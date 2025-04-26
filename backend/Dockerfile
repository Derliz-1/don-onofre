# Imagen base oficial de PHP + Apache
FROM php:8.2-apache

# Instalamos extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite mbstring zip

# Copiamos configuración personalizada de Apache
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Copiamos todos los archivos del proyecto
COPY . /var/www/html

# Damos permisos correctos a Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Activamos módulo rewrite
RUN a2enmod rewrite

# Exponemos el puerto 80
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]