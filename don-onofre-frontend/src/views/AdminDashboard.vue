<template>
  <div class="admin">
    <AdminNav />

    <div v-if="mostrarDashboard">
      <h2>🔄 Dashboard</h2>

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

      <div v-if="ordenesFiltradas.length">
        <h3>📝 Órdenes {{ filtroTexto }}</h3>
        <ul>
          <li v-for="orden in ordenesFiltradas" :key="orden.id">
            #{{ orden.id }} - {{ orden.estado }} - {{ orden.cliente?.nombre_completo || 'Sin Cliente' }} - Gs. {{ orden.total ? orden.total.toLocaleString() : 0 }}
          </li>
        </ul>
      </div>

      <div v-else>
        <h3>Últimos pedidos:</h3>
        <ul>
          <li v-for="orden in resumen.ultimos_pedidos || []" :key="orden.id">
            #{{ orden.id }} - {{ orden.estado }} - {{ orden.cliente?.nombre_completo || 'Sin Cliente' }} - Gs. {{ orden.total ? orden.total.toLocaleString() : 0 }}
          </li>
        </ul>
      </div>
    </div>

    <router-view />
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import api from '../api.js' // ✅ Usamos el axios con token automático
import AdminNav from '../components/AdminNav.vue'

const resumen = ref({})
const filtro = ref('todas')
const route = useRoute()

const mostrarDashboard = computed(() => route.path === '/admin')

const seleccionarFiltro = (tipo) => {
  filtro.value = tipo
}

const estadoMap = {
  pagadas: 'pagado',
  pendientes: 'pendiente',
  canceladas: 'cancelado'
}

const ordenesFiltradas = computed(() => {
  if (filtro.value === 'todas') return resumen.value.todas || []

  const estadoFiltro = estadoMap[filtro.value]
  return (resumen.value.todas || []).filter(orden => orden.estado === estadoFiltro)
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
.stats {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 20px;
}
.card {
  background: #eee;
  padding: 12px;
  border-radius: 8px;
  flex: 1;
  text-align: center;
  cursor: pointer;
  font-weight: bold;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.card:hover {
  background: #ddd;
}
ul {
  list-style: none;
  padding: 0;
}
li {
  background: #fafafa;
  margin-bottom: 8px;
  padding: 10px;
  border-radius: 6px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #007bff;
}
</style>