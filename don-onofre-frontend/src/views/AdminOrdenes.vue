<template>
  <div class="admin">
    <AdminNav />
    <h2>📖 Órdenes</h2>

    <div class="filtros">
      <label for="filtroFecha">Filtrar por fecha:</label>
      <input type="date" id="filtroFecha" v-model="filtroFecha" />
      <button @click="filtrarOrdenes">Aplicar filtro</button>
      <button @click="limpiarFiltro">Limpiar</button>
    </div>

    <ul>
      <li v-for="orden in ordenesFiltradas" :key="orden.id" class="orden-item">
        <h3>
          🧾 Orden #{{ orden.id }} -
          <span class="estado" :class="orden.estado">{{ estadoTexto(orden.estado) }}</span> -
          Gs. {{ orden.total?.toLocaleString() }}
        </h3>

        <p><strong>Cliente:</strong> {{ orden.cliente.nombre_completo }} | {{ orden.cliente.email }} | {{ orden.cliente.telefono }}</p>
        <p><strong>Dirección:</strong> {{ orden.cliente.direccion }}, {{ orden.cliente.ciudad }}, {{ orden.cliente.pais }}</p>
        <p><strong>Fecha:</strong> {{ orden.created_at.slice(0, 10) }}</p>
        <p><strong>Nota:</strong> {{ orden.notas || 'Sin nota' }}</p>

        <div class="productos">
          <p><strong>Productos:</strong></p>
          <ul>
            <li v-for="producto in orden.productos" :key="producto.id">
              {{ producto.nombre }} × {{ producto.pivot.cantidad }} - Subtotal: Gs. {{ producto.pivot.subtotal?.toLocaleString() }}
            </li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../api'

const ordenes = ref([])
const filtroFecha = ref('')

const ordenesFiltradas = computed(() => {
  if (!filtroFecha.value) return ordenes.value
  return ordenes.value.filter(o => o.created_at.startsWith(filtroFecha.value))
})

const filtrarOrdenes = () => {
  // Ya se filtra con computed automáticamente
}

const limpiarFiltro = () => {
  filtroFecha.value = ''
}

const estadoTexto = (estado) => {
  switch (estado) {
    case 'pendiente': return '🕑 Pendiente'
    case 'pagado': return '✅ Pagado'
    case 'cancelado': return '❌ Cancelado'
    default: return estado
  }
}

onMounted(async () => {
  const res = await api.get('/ordenes')
  ordenes.value = res.data
})
</script>

<style scoped>
.admin {
  padding: 20px;
}
.filtros {
  margin-bottom: 20px;
  display: flex;
  gap: 10px;
  align-items: center;
}
.orden-item {
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 15px;
  background: #f9f9f9;
}
.estado {
  font-weight: bold;
}
.estado.pendiente {
  color: orange;
}
.estado.pagado {
  color: green;
}
.estado.cancelado {
  color: red;
}
</style>
