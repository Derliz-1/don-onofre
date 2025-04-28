FROM php:8.2-apache

# Instalar dependencias del sistema necesarias
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

# Configurar extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo pdo_mysql pdo_sqlite mbstring zip

# Copiar apache.conf
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Copiar el proyecto
COPY . /var/www/html

# Dar permisos correctos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Activar módulos de Apache
RUN a2enmod rewrite

# Crear base de datos SQLite si no existe
RUN mkdir -p /var/www/database && touch /var/www/database/database.sqlite

# Migrar base de datos
RUN php artisan migrate --force

# Exponer puerto 80
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]
