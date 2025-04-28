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

# Instalar Composer manualmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar configuración apache
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Copiar todo el proyecto
COPY . /var/www/html

# Ir al directorio del proyecto
WORKDIR /var/www/html

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Dar permisos correctos a storage y cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Activar módulo rewrite de Apache
RUN a2enmod rewrite

# Exponer puerto
EXPOSE 80

# Comando de inicio: migrar base de datos y arrancar Apache
CMD php artisan migrate --force && apache2-foreground