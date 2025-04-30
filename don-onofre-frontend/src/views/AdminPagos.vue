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
        <h3>
          Pago #{{ pago.id }} -
          <span class="estado" :class="pago.estado">
            {{ estadoTexto(pago.estado) }}
          </span>
        </h3>
        <p><strong>Referencia:</strong> {{ pago.referencia_pago }}</p>
        <p><strong>Orden:</strong> #{{ pago.orden_id }} | Total: Gs. {{ pago.orden?.total?.toLocaleString() }}</p>
        <p>
          <strong>Cliente:</strong>
          {{ pago.orden?.cliente?.nombre_completo }} |
          {{ pago.orden?.cliente?.email }} |
          {{ pago.orden?.cliente?.telefono }}
        </p>
        <p>
          <strong>Dirección:</strong>
          {{ pago.orden?.cliente?.direccion }},
          {{ pago.orden?.cliente?.ciudad }},
          {{ pago.orden?.cliente?.pais }}
        </p>
        <p>
          <strong>Fecha:</strong>
          {{ pago.confirmado_en
            ? new Date(pago.confirmado_en).toLocaleDateString('es-PY')
            : '❌ No confirmado' }}
        </p>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api'

const pagos = ref([])
const filtroEstado = ref('')
const filtroFecha = ref('')

const estadoTexto = (estado) => {
  switch (estado) {
    case 'pendiente': return '🕑 Pendiente'
    case 'exitoso': return '✅ Exitoso'
    case 'cancelado': return '❌ Cancelado'
    case 'expirado': return '⌛ Expirado'
    case 'fallido': return '⚠️ Fallido'
    default: return estado
  }
}

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
  padding: 20px;
}

.filtros {
  margin-bottom: 20px;
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  align-items: center;
}

.filtros label {
  font-weight: bold;
}

.pago-item {
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 15px;
  background: #f8f9fa;
}

.estado {
  font-weight: bold;
}

.estado.pendiente {
  color: orange;
}

.estado.exitoso {
  color: green;
}

.estado.cancelado,
.estado.expirado,
.estado.fallido {
  color: red;
}
</style>