FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libsqlite3-dev \
    sqlite3 \
    zip \
    unzip \
    git \
    curl

# Configuramos correctamente GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Instalamos extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring zip gd

# Copiamos apache.conf
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Copiamos archivos del proyecto
COPY . /var/www/html

# Seteamos permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Activamos módulos de Apache
RUN a2enmod rewrite

# Exponemos el puerto
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]
