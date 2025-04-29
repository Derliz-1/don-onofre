import { createRouter, createWebHistory } from 'vue-router'
import Home from './views/Home.vue'
import AdminLogin from './views/AdminLogin.vue'
import AdminDashboard from './views/AdminDashboard.vue'
import AdminProductos from './views/AdminProductos.vue'
import AdminOrdenes from './views/AdminOrdenes.vue'
import AdminPagos from './views/AdminPagos.vue'
import VerOrden from './views/VerOrden.vue'

const routes = [
  { path: '/', component: Home },
  { path: '/orden/:id', component: VerOrden },

  { path: '/admin/login', component: AdminLogin },
  { path: '/admin', component: AdminDashboard },
  { path: '/admin/productos', component: AdminProductos },
  { path: '/admin/ordenes', component: AdminOrdenes },
  { path: '/admin/pagos', component: AdminPagos },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router