# Don Onofre - Backend (Laravel)

Este es el backend del sistema "Don Onofre", desarrollado con Laravel 10 y MySQL.

---

## Requisitos
- PHP >= 8.1
- Composer
- MySQL
- Node.js y npm (opcional)
- Extensiones PHP requeridas: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON

---

## Instalación paso a paso

1. Clonar el repositorio o copiar la carpeta `backend`.

2. Acceder a la carpeta del backend:
```bash
cd backend
```

3. Instalar las dependencias PHP:
```bash
composer install
```

4. Copiar el archivo de entorno:
```bash
cp .env.example .env
```

5. Configurar el archivo `.env` con los datos de tu base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dononofre
DB_USERNAME=root
DB_PASSWORD=
```

6. Generar la clave de la aplicación:
```bash
php artisan key:generate
```

7. Ejecutar migraciones:
```bash
php artisan migrate
```

8. Crear enlace simbólico para acceso a archivos públicos (imágenes):
```bash
php artisan storage:link
```

---

## Configuración de CORS

9. Publicar archivo de configuración (si es necesario):
```bash
php artisan vendor:publish --tag="cors"
```

10. En `config/cors.php` asegurarse de permitir el frontend: `http://localhost:5173`

---

## Laravel Sanctum

11. Instalar Sanctum (si no está):
```bash
composer require laravel/sanctum
```

12. Publicar archivos de configuración:
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

13. Ejecutar migraciones:
```bash
php artisan migrate
```

14. Middleware para Sanctum en `app/Http/Kernel.php`:
```php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

---

## Ejecutar el servidor local

15. Iniciar el servidor:
```bash
php artisan serve
```

La API estará disponible en: [http://localhost:8000](http://localhost:8000)
