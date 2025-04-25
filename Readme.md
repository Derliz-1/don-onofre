# 🛒 Don Onofre

**Don Onofre** es una tienda online completa con panel de administración, desarrollada utilizando **Vue 3**, **Laravel 10** y **MySQL**. El sistema permite a los clientes comprar productos fácilmente y a los administradores gestionar productos, órdenes y pagos desde un panel seguro.

---

## 🚀 Tecnologías utilizadas

- 🧠 **Frontend**: Vue 3, Vue Router, Axios, Vite
- ⚙️ **Backend**: Laravel 10, Laravel Sanctum, CORS, Eloquent ORM
- 🗄️ **Base de Datos**: MySQL
- 🎨 **Estilos**: CSS personalizado
- 🛠️ **Herramientas de Build**: Vite (para frontend)

---

## 📸 Capturas de Pantalla

### 🏪 Vista de productos (Cliente)
![Productos](screenshots/home-productos.png)

### 🛒 Carrito de compras
![Carrito](screenshots/carrito-formulario.png)

### 💳 Confirmación de pago
![Pago](screenshots/confirmar-pago.png)

### 💳 Confirmación de pago
![Pago](screenshots/coprobante.png)

### 🔐 Login del administrador
![Login Admin](screenshots/admin-login.png)

### 📊 Dashboard del administrador
![Dashboard](screenshots/admin-dashboard.png)

### 🛍️ Gestión de productos
![Productos Admin](screenshots/admin-productos.png)

### 📦 Gestión de órdenes
![Órdenes](screenshots/admin-ordenes.png)

### 📦 Gestión de órdenes
![Órdenes](screenshots/admin-pagos.png)


---

## ⚙️ Instalación del Proyecto

### 🔧 Backend (Laravel)

```bash
cd backend
composer install
cp .env.example .env
# Editar .env con tus datos de MySQL
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan vendor:publish --tag="cors"
php artisan serve
```

---

### 🌐 Frontend (Vue.js)

```bash
cd don-onofre-frontend
npm install
cp .env.example .env
# Editar .env → VITE_API_URL=http://localhost:8000/api
npm run dev
```

---

## 👥 Acceso al sistema

### Cliente

- URL: [http://localhost:5173](http://localhost:5173)

### Administrador

- URL: [http://localhost:5173/admin/login](http://localhost:5173/admin/login)
- **Correo:** `admin@example.com`
- **Contraseña:** `admin123`

---

## 📁 Estructura del Proyecto

```
don-onofre/
├── backend/                # Backend Laravel API
└── don-onofre-frontend/    # Frontend Vue.js SPA
```

---

## 📄 Documentación

Toda la documentación detallada se encuentra en el archivo `DOCUMENTACION-COMPLETA-DON-ONOFRE.pdf` incluido en este repositorio.

---

## ✍️ Autor

**Derlis Ramón Dominguez Monges**  
📧 derlizdominguez@gmail.com  
🔗 [LinkedIn](https://www.linkedin.com/in/derlis-dominguez-ab4022240)

---

## 📝 Licencia

Este proyecto está bajo la licencia MIT.
