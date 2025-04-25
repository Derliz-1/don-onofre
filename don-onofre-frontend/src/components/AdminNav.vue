<template>
  <nav class="admin-nav">
    <RouterLink to="/admin">🏠 Dashboard</RouterLink>
    <RouterLink to="/admin/productos">📦 Productos</RouterLink>
    <RouterLink to="/admin/ordenes">📑 Órdenes</RouterLink>
    <RouterLink to="/admin/pagos">💳 Pagos</RouterLink>
    <a href="#" @click.prevent="cerrarSesion">🚪 Cerrar sesión</a>
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
  gap: 12px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}
button {
  background: #c0392b;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
}
button:hover {
  background: #e74c3c;
}
</style>