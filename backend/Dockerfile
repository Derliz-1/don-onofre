FROM php:8.2

# Instalar librerías necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libsqlite3-dev \
    unzip \
    zip \
    curl \
    git

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear directorio de trabajo
WORKDIR /var/www

# Copiar todo el código
COPY . .

# Instalar dependencias PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copiar .env y generar APP_KEY
RUN cp .env.example .env && php artisan key:generate

# Exponer la app
CMD php artisan serve --host=0.0.0.0 --port=8000
