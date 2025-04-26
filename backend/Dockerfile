# Imagen base
FROM php:8.2-apache

# Instalar extensiones necesarias
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
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite mbstring zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar apache.conf
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Copiar archivos de la app
COPY . /var/www/html

# Ir al directorio de la app
WORKDIR /var/www/html

# Instalar dependencias de Laravel
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Dar permisos correctos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/database


# Activar módulo rewrite de Apache
RUN a2enmod rewrite

# Exponer el puerto 80
EXPOSE 80

# Comando para iniciar apache
CMD ["apache2-foreground"]
