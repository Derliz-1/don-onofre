FROM php:8.2-fpm

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    sqlite3 \
    libsqlite3-dev \
    libzip-dev

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www

# Copiar archivos
COPY . .

# Instalar dependencias del proyecto
RUN composer install --no-dev --optimize-autoloader

# Generar clave de app
RUN php artisan key:generate

# Migrar la base de datos
RUN php artisan migrate --force || true

# Puerto
EXPOSE 8000

# Comando por defecto
CMD php artisan serve --host=0.0.0.0 --port=8000
