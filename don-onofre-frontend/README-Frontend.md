# Don Onofre - Frontend (Vue.js)

Este es el frontend de la tienda online "Don Onofre", construido con Vue 3.

---

## Requisitos
- Node.js >= 18.x
- npm >= 9.x

---

## Instalación paso a paso

1. Clonar o copiar la carpeta `don-onofre-frontend`.

2. Acceder al proyecto:
```bash
cd don-onofre-frontend
```

3. Instalar dependencias:
```bash
npm install
```

4. Crear el archivo `.env`:
```bash
cp .env.example .env
```

5. Configurar el archivo `.env`:
```env
VITE_API_URL=http://localhost:8000/api
```

6. Iniciar el servidor de desarrollo:
```bash
npm run dev
```

Acceder desde: [http://localhost:5173](http://localhost:5173)

---

## Librerías utilizadas

- **axios**: para consumo de la API.
- **vue-router**: para navegación entre vistas.
- **sweetalert2**: para mostrar alertas.
- **dotenv**: configuración por entorno.

---

## Estructura del proyecto

- `/components`: tarjetas, carrito, formularios.
- `/views`: vistas principales.
- `/router/index.js`: rutas.
- `App.vue` y `main.js`: configuración general.