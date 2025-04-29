<template>
  <nav class="admin-nav">
    <RouterLink to="/admin" class="nav-link">🏠 Dashboard</RouterLink>
    <RouterLink to="/admin/productos" class="nav-link">📦 Productos</RouterLink>
    <RouterLink to="/admin/ordenes" class="nav-link">📑 Órdenes</RouterLink>
    <RouterLink to="/admin/pagos" class="nav-link">💳 Pagos</RouterLink>
    <button class="btn-logout" @click.prevent="cerrarSesion">🚪 Cerrar sesión</button>
  </nav>
</template>

<script setup>
import { useRouter } from 'vue-router'
const router = useRouter()

const cerrarSesion = () => {
  localStorage.removeItem('admin_token')
  router.push('/admin/login')
}
</script>

<style scoped>
.admin-nav {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 30px;
  justify-content: center;
}

.nav-link {
  background: #3498db;
  color: white;
  padding: 10px 18px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: bold;
  font-size: 16px;
  transition: background 0.3s, transform 0.2s;
}
.nav-link:hover {
  background: #2980b9;
  transform: scale(1.05);
}

.btn-logout {
  background: #e74c3c;
  color: white;
  padding: 10px 18px;
  border: none;
  border-radius: 8px;
  font-weight: bold;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.3s, transform 0.2s;
}
.btn-logout:hover {
  background: #c0392b;
  transform: scale(1.05);
}
</style>