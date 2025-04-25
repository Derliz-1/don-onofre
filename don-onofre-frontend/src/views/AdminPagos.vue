<template>
  <div class="admin">
    <AdminNav />
    <h2>💳 Pagos</h2>

    <div class="filtros">
      <label for="estado">Estado:</label>
      <select v-model="filtroEstado" id="estado">
        <option value="">Todos</option>
        <option value="pendiente">Pendiente</option>
        <option value="exitoso">Exitoso</option>
        <option value="cancelado">Cancelado</option>
        <option value="expirado">Expirado</option>
        <option value="fallido">Fallido</option>
      </select>

      <label for="fecha">Fecha:</label>
      <input type="date" v-model="filtroFecha" id="fecha" />

      <button @click="filtrarPagos">Filtrar</button>
      <button @click="limpiarFiltros">Limpiar</button>
    </div>

    <ul>
      <li v-for="pago in pagos" :key="pago.id" class="pago-item">
        <h3>Pago #{{ pago.id }} - <span class="estado">{{ pago.estado }}</span></h3>
        <p><strong>Referencia:</strong> {{ pago.referencia_pago }}</p>
        <p><strong>Orden:</strong> #{{ pago.orden_id }} | Total: Gs. {{ pago.orden?.total }}</p>
        <p><strong>Cliente:</strong> {{ pago.orden?.cliente?.nombre_completo }} | {{ pago.orden?.cliente?.email }} | {{ pago.orden?.cliente?.telefono }}</p>
        <p><strong>Dirección:</strong> {{ pago.orden?.cliente?.direccion }}, {{ pago.orden?.cliente?.ciudad }}, {{ pago.orden?.cliente?.pais }}</p>
        <p><strong>Fecha:</strong> {{ pago.confirmado_en ? pago.confirmado_en.slice(0, 10) : '❌ No confirmado' }}</p>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api/admin'

const pagos = ref([])
const filtroEstado = ref('')
const filtroFecha = ref('')

const filtrarPagos = async () => {
  const params = {}
  if (filtroEstado.value) params.estado = filtroEstado.value
  if (filtroFecha.value) params.fecha = filtroFecha.value

  const res = await api.get('/pagos', { params })
  pagos.value = res.data
}

const limpiarFiltros = async () => {
  filtroEstado.value = ''
  filtroFecha.value = ''
  const res = await api.get('/pagos')
  pagos.value = res.data
}

onMounted(async () => {
  const res = await api.get('/pagos')
  pagos.value = res.data
})
</script>

<style scoped>
.admin {
  max-width: 900px;
  margin: auto;
  padding: 20px;
}
.filtros {
  margin-bottom: 20px;
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  align-items: center;
}
.filtros select,
.filtros input[type="date"] {
  padding: 6px;
  border-radius: 4px;
  border: 1px solid #ccc;
}
.filtros button {
  padding: 6px 12px;
  font-weight: bold;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
.filtros button:hover {
  background-color: #0056b3;
}
.pago-item {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 20px;
  background: #fefefe;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.pago-item h3 {
  margin-bottom: 10px;
}
.estado {
  text-transform: capitalize;
  color: #17a2b8;
}
</style>