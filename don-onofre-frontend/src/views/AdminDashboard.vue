<template>
  <div class="admin">
    <AdminNav />

    <div v-if="route.path === '/admin'">
      <h2 class="titulo">🔄 Dashboard</h2>

      <div class="stats">
        <div class="card" @click="seleccionarFiltro('todas')">
          Total Órdenes: {{ resumen.total_ordenes || 0 }}
        </div>
        <div class="card" @click="seleccionarFiltro('pagadas')">
          Pagadas: {{ resumen.pagadas || 0 }}
        </div>
        <div class="card" @click="seleccionarFiltro('pendientes')">
          Pendientes: {{ resumen.pendientes || 0 }}
        </div>
        <div class="card" @click="seleccionarFiltro('canceladas')">
          Canceladas: {{ resumen.canceladas || 0 }}
        </div>
        <div class="card">
          Recaudado: Gs. {{ resumen.recaudado ? resumen.recaudado.toLocaleString() : 0 }}
        </div>
      </div>

      <!-- Lista filtrada -->
      <div v-if="ordenesFiltradas.length">
        <h3>📝 Órdenes {{ filtroTexto }}</h3>
        <ul class="lista">
          <li v-for="orden in ordenesFiltradas" :key="orden.id">
            #{{ orden.id }} - {{ orden.estado }} - {{ orden.cliente?.nombre_completo || 'Sin Cliente' }} - Gs. {{ orden.total ? orden.total.toLocaleString() : 0 }}
          </li>
        </ul>
      </div>

      <!-- Lista de últimos pedidos si no hay filtro -->
      <div v-else>
        <h3>Últimos pedidos:</h3>
        <ul class="lista">
          <li v-for="orden in resumen.ultimos_pedidos || []" :key="orden.id">
            #{{ orden.id }} - {{ orden.estado }} - {{ orden.cliente?.nombre_completo || 'Sin Cliente' }} - Gs. {{ orden.total ? orden.total.toLocaleString() : 0 }}
          </li>
        </ul>
      </div>
    </div>

    <!-- Rutas hijas -->
    <router-view />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../api/admin'
import AdminNav from '../components/AdminNav.vue'

const resumen = ref({})
const filtro = ref('todas')
const route = useRoute()

const estadoMap = {
  pagadas: 'pagado',
  pendientes: 'pendiente',
  canceladas: 'cancelado'
}

const seleccionarFiltro = (tipo) => {
  filtro.value = tipo
}

const ordenesFiltradas = computed(() => {
  const todas = resumen.value.todas || []
  if (filtro.value === 'todas') return todas
  const estadoFiltro = estadoMap[filtro.value]
  return todas.filter(orden => orden.estado === estadoFiltro)
})

const filtroTexto = computed(() => {
  switch (filtro.value) {
    case 'pagadas': return 'Pagadas'
    case 'pendientes': return 'Pendientes'
    case 'canceladas': return 'Canceladas'
    default: return 'Totales'
  }
})

onMounted(async () => {
  try {
    const res = await api.get('/admin/dashboard')
    resumen.value = res.data
  } catch (error) {
    console.error('Error cargando dashboard:', error)
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