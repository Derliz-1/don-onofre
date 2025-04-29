<template>
  <div class="admin">
    <AdminNav />

    <div v-if="route.path === '/admin'">
      <h2 class="titulo">🔄 Dashboard</h2>

      <div class="stats">
        <div class="card" @click="aplicarFiltro('todas')">Total Órdenes: {{ resumen.total_ordenes || 0 }}</div>
        <div class="card" @click="aplicarFiltro('pagadas')">Pagadas: {{ resumen.pagadas || 0 }}</div>
        <div class="card" @click="aplicarFiltro('pendientes')">Pendientes: {{ resumen.pendientes || 0 }}</div>
        <div class="card" @click="aplicarFiltro('canceladas')">Canceladas: {{ resumen.canceladas || 0 }}</div>
        <div class="card">Recaudado: Gs. {{ resumen.recaudado?.toLocaleString() || 0 }}</div>
      </div>

      <div v-if="filtro !== 'todas' && ordenesFiltradas.length">
        <h3>Órdenes {{ filtroTexto }}</h3>
        <ul class="lista">
          <li v-for="orden in ordenesFiltradas" :key="orden.id">
            #{{ orden.id }} - {{ orden.estado }} - {{ orden.cliente?.nombre_completo || 'Sin Cliente' }} - Gs. {{ orden.total?.toLocaleString() || 0 }}
          </li>
        </ul>
      </div>

      <div v-else>
        <h3>Últimos pedidos:</h3>
        <ul class="lista">
          <li v-for="orden in resumen.ultimos_pedidos || []" :key="orden.id">
            #{{ orden.id }} - {{ orden.estado }} - {{ orden.cliente?.nombre_completo || 'Sin Cliente' }} - Gs. {{ orden.total?.toLocaleString() || 0 }}
          </li>
        </ul>
      </div>
    </div>

    <router-view />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../api/admin'
import AdminNav from '../components/AdminNav.vue'

const route = useRoute()
const resumen = ref({})
const filtro = ref('todas')

const aplicarFiltro = (tipo) => {
  filtro.value = tipo
}

const ordenesFiltradas = computed(() => {
  if (!resumen.value.todas) return []
  if (filtro.value === 'todas') return []

  const estado = {
    pagadas: 'pagado',
    pendientes: 'pendiente',
    canceladas: 'cancelado'
  }[filtro.value]

  return resumen.value.todas.filter(o => o.estado === estado)
})

const filtroTexto = computed(() => {
  return {
    pagadas: 'Pagadas',
    pendientes: 'Pendientes',
    canceladas: 'Canceladas'
  }[filtro.value] || ''
})

onMounted(async () => {
  try {
    const res = await api.get('/admin/dashboard')
    resumen.value = res.data
  } catch (error) {
    console.error('Error cargando resumen:', error)
  }
})
</script>

<style scoped>
.admin {
  max-width: 1000px;
  margin: auto;
  padding: 20px;
}
.titulo {
  text-align: center;
  margin-bottom: 20px;
  font-size: 28px;
  color: #007bff;
}
.stats {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 20px;
}
.card {
  background: #f8f9fa;
  padding: 14px;
  border-radius: 10px;
  flex: 1 1 180px;
  text-align: center;
  cursor: pointer;
  font-weight: bold;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  transition: background 0.3s, transform 0.2s;
}
.card:hover {
  background: #e2e6ea;
  transform: scale(1.03);
}
.lista {
  list-style: none;
  padding: 0;
}
.lista li {
  background: #fff;
  margin-bottom: 10px;
  padding: 12px;
  border-radius: 8px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}
</style>