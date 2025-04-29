import { createRouter, createWebHistory } from 'vue-router'
import Home from './views/Home.vue'
import VerOrden from './views/VerOrden.vue'
import AdminLogin from './views/AdminLogin.vue'
import AdminDashboard from './views/AdminDashboard.vue'
import AdminProductos from './views/AdminProductos.vue'
import AdminOrdenes from './views/AdminOrdenes.vue'
import AdminPagos from './views/AdminPagos.vue'

const routes = [
  { path: '/', component: Home },
  { path: '/orden/:id', component: VerOrden },

  { path: '/admin/login', component: AdminLogin },
  {
    path: '/admin',
    component: AdminDashboard,
    meta: { requiresAuth: true },
    children: [
      { path: '', component: AdminOrdenes },
      { path: 'productos', component: AdminProductos },
      { path: 'ordenes', component: AdminOrdenes },
      { path: 'pagos', component: AdminPagos },
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Proteger rutas del admin
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth) {
    const token = localStorage.getItem('admin_token')
    if (!token) {
      return next('/admin/login')
    }
  }
  next()
})

export default router