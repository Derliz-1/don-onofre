<template>
  <nav class="admin-nav">
    <RouterLink to="/admin" active-class="activo">🏠 Dashboard</RouterLink>
    <RouterLink to="/admin/productos" active-class="activo">📦 Productos</RouterLink>
    <RouterLink to="/admin/ordenes" active-class="activo">📑 Órdenes</RouterLink>
    <RouterLink to="/admin/pagos" active-class="activo">💳 Pagos</RouterLink>
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
.router-link-active, .activo {
  font-weight: bold;
  color: #007bff;
}
a, .router-link-active, .router-link-exact-active {
  text-decoration: none;
  color: #333;
}
a:hover {
  text-decoration: underline;
}
</style>