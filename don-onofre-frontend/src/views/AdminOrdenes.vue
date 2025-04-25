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
        <h3>🧾 Orden #{{ orden.id }} - <span class="estado">{{ orden.estado }}</span> - Gs. {{ orden.total }}</h3>
        <p><strong>Cliente:</strong> {{ orden.cliente.nombre_completo }} | {{ orden.cliente.email }} | {{ orden.cliente.telefono }}</p>
        <p><strong>Dirección:</strong> {{ orden.cliente.direccion }}, {{ orden.cliente.ciudad }}, {{ orden.cliente.pais }}</p>
        <p><strong>Fecha:</strong> {{ orden.created_at.slice(0, 10) }}</p>
        <p><strong>Nota:</strong> {{ orden.notas || 'Sin nota' }}</p>

        <div class="productos">
          <p><strong>Productos:</strong></p>
          <ul>
            <li v-for="producto in orden.productos" :key="producto.id">
              {{ producto.nombre }} × {{ producto.pivot.cantidad }} - Subtotal: Gs. {{ producto.pivot.subtotal }}
            </li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../api/admin'

const ordenes = ref([])
const filtroFecha = ref('')

const ordenesFiltradas = computed(() => {
  if (!filtroFecha.value) return ordenes.value
  return ordenes.value.filter(o => o.created_at.startsWith(filtroFecha.value))
})

const filtrarOrdenes = () => {
}

const limpiarFiltro = () => {
  filtroFecha.value = ''
}

onMounted(async () => {
  const res = await api.get('/ordenes')
  ordenes.value = res.data
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
  gap: 10px;
  align-items: center;
}
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
.orden-item {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 20px;
  background: #fefefe;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.orden-item h3 {
  margin-bottom: 10px;
}
.estado {
  text-transform: capitalize;
  color: #17a2b8;
}
.productos ul {
  padding-left: 20px;
  margin: 0;
}
</style>
