# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev

# Extensiones PHP necesarias
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql pdo_sqlite mbstring zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia los archivos del backend al contenedor
COPY . /var/www/html

# Configura permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Establece directorio de trabajo
WORKDIR /var/www/html

# Copia configuración personalizada de Apache
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Habilita mod_rewrite de Apache (necesario para Laravel)
RUN a2enmod rewrite

# Instala dependencias PHP del proyecto
RUN composer install --no-dev --prefer-dist --no-scripts --no-interaction

# Expone el puerto 80
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]
