<template>
  <div class="admin">
    <AdminNav />

    <div v-if="mostrarDashboard">
      <h2 class="titulo">🔄 Dashboard</h2>

      <div class="stats">
        <div
          class="card card-todas"
          @click="seleccionarFiltro('todas')"
        >
          Total Órdenes
          <span>{{ resumen.total_ordenes }}</span>
        </div>

        <div
          class="card card-pagadas"
          @click="seleccionarFiltro('pagadas')"
        >
          Pagadas
          <span>{{ resumen.pagadas }}</span>
        </div>

        <div
          class="card card-pendientes"
          @click="seleccionarFiltro('pendientes')"
        >
          Pendientes
          <span>{{ resumen.pendientes }}</span>
        </div>

        <div
          class="card card-canceladas"
          @click="seleccionarFiltro('canceladas')"
        >
          Canceladas
          <span>{{ resumen.canceladas }}</span>
        </div>

        <div class="card card-recaudado">
          Recaudado
          <span>Gs. {{ resumen.recaudado.toLocaleString() }}</span>
        </div>
      </div>

      <div v-if="ordenesFiltradas.length">
        <h3>📝 Órdenes {{ filtroTexto }}</h3>
        <ul class="ordenes">
          <li v-for="orden in ordenesFiltradas" :key="orden.id">
            #{{ orden.id }} - {{ orden.estado }} - {{ orden.cliente.nombre }} {{ orden.cliente.apellido }} - Gs. {{ orden.total.toLocaleString() }}
          </li>
        </ul>
      </div>

      <div v-else>
        <h3>Últimos pedidos:</h3>
        <ul class="ordenes">
          <li v-for="orden in resumen.ultimos_pedidos || []" :key="orden.id">
            #{{ orden.id }} - {{ orden.estado }} - {{ orden.cliente.nombre }} {{ orden.cliente.apellido }} - Gs. {{ orden.total.toLocaleString() }}
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
import api from '../api/admin'
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
  if (!filtro.value || filtro.value === 'todas') return resumen.value.todas || []
  const estadoFiltro = estadoMap[filtro.value]
  return (resumen.value.todas || []).filter(orden => orden.estado === estadoFiltro)
})

const filtroTexto = computed(() => {
  if (filtro.value === 'pagadas') return 'Pagadas'
  if (filtro.value === 'pendientes') return 'Pendientes'
  if (filtro.value === 'canceladas') return 'Canceladas'
  return 'Totales'
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
  margin: 20px auto;
  padding: 20px;
}
.titulo {
  text-align: center;
  font-size: 28px;
  margin-bottom: 20px;
  color: #333;
}
.stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 15px;
  margin-bottom: 30px;
}
.card {
  background: #ffffff;
  padding: 15px;
  border-radius: 10px;
  text-align: center;
  font-weight: bold;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  cursor: pointer;
  transition: transform 0.3s, background 0.3s;
}
.card span {
  display: block;
  font-size: 20px;
  margin-top: 10px;
  font-weight: normal;
}
.card:hover {
  transform: translateY(-5px);
}
.card-todas {
  background: linear-gradient(to right, #00c6ff, #0072ff);
  color: white;
}
.card-pagadas {
  background: linear-gradient(to right, #00b09b, #96c93d);
  color: white;
}
.card-pendientes {
  background: linear-gradient(to right, #f7971e, #ffd200);
  color: white;
}
.card-canceladas {
  background: linear-gradient(to right, #f953c6, #b91d73);
  color: white;
}
.card-recaudado {
  background: linear-gradient(to right, #f12711, #f5af19);
  color: white;
}
.ordenes {
  list-style: none;
  padding: 0;
}
.ordenes li {
  background: #f9f9f9;
  margin-bottom: 10px;
  padding: 12px;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.08);
  transition: background 0.3s;
}
.ordenes li:hover {
  background: #f0f0f0;
}
</style>