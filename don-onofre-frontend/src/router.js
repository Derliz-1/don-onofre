import { createRouter, createWebHistory } from 'vue-router'

import Home from './views/Home.vue'
import VerOrden from './views/VerOrden.vue'
import Confirmacion from './views/Confirmacion.vue'

import AdminLogin from './views/AdminLogin.vue'
import AdminDashboard from './views/AdminDashboard.vue'
import AdminProductos from './views/AdminProductos.vue'
import AdminOrdenes from './views/AdminOrdenes.vue'
import AdminPagos from './views/AdminPagos.vue'

const routes = [
  // 🛍️ Cliente
  { path: '/', name: 'Home', component: Home },
  { path: '/orden/:id', name: 'VerOrden', component: VerOrden },
  { path: '/confirmacion/:id', name: 'Confirmacion', component: Confirmacion },

  // 🔐 Admin
  { path: '/admin/login', name: 'AdminLogin', component: AdminLogin },
  { path: '/admin', name: 'AdminDashboard', component: AdminDashboard },
  { path: '/admin/productos', name: 'AdminProductos', component: AdminProductos },
  { path: '/admin/ordenes', name: 'AdminOrdenes', component: AdminOrdenes },
  { path: '/admin/pagos', name: 'AdminPagos', component: AdminPagos }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router