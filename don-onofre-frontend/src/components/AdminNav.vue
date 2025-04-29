<template>
  <nav class="admin-nav">
    <RouterLink to="/admin" exact-active-class="active">🏠 Dashboard</RouterLink>
    <RouterLink to="/admin/productos" exact-active-class="active">📦 Productos</RouterLink>
    <RouterLink to="/admin/ordenes" exact-active-class="active">📑 Órdenes</RouterLink>
    <RouterLink to="/admin/pagos" exact-active-class="active">💳 Pagos</RouterLink>
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
a, .router-link-active {
  text-decoration: none;
  color: #007bff;
}
.active {
  font-weight: bold;
  color: #0056b3;
  text-decoration: underline;
}
</style>