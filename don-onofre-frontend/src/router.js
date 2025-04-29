import { createRouter, createWebHistory } from 'vue-router'
import Home from './views/Home.vue'
import VerOrden from './views/VerOrden.vue'
import AdminLogin from './views/AdminLogin.vue'
import AdminDashboard from './views/AdminDashboard.vue'
import AdminProductos from './views/AdminProductos.vue'
import AdminOrdenes from './views/AdminOrdenes.vue'
import AdminPagos from './views/AdminPagos.vue'

// Middleware para proteger rutas admin
function requireAdmin(to, from, next) {
  const token = localStorage.getItem('admin_token')
  if (token) {
    next()
  } else {
    next('/admin/login')
  }
}

const routes = [
  { path: '/', component: Home },
  { path: '/orden/:id', component: VerOrden },
  { path: '/admin/login', component: AdminLogin },
  {
    path: '/admin',
    component: AdminDashboard,
    beforeEnter: requireAdmin
  },
  {
    path: '/admin/productos',
    component: AdminProductos,
    beforeEnter: requireAdmin
  },
  {
    path: '/admin/ordenes',
    component: AdminOrdenes,
    beforeEnter: requireAdmin
  },
  {
    path: '/admin/pagos',
    component: AdminPagos,
    beforeEnter: requireAdmin
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router