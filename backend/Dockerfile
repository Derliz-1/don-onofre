FROM php:8.2

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear directorio de trabajo
WORKDIR /var/www

# Copiar archivos
COPY . .

# Instalar dependencias PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copiar .env de ejemplo y generar key
RUN cp .env.example .env \
    && php artisan key:generate

# Migraciones y seeder
RUN php artisan migrate --force \
    && php artisan db:seed --class=AdminUserSeeder

# Iniciar servidor
CMD php artisan serve --host=0.0.0.0 --port=8000