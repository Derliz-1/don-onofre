<template>
  <div class="ver-orden">
    <h1>🧾 Comprobante de Orden</h1>

    <div v-if="cargando">Cargando orden...</div>
    <div v-else-if="error">{{ error }}</div>
    <div v-else-if="orden">
      <h2>Orden #{{ orden.id }}</h2>

      <p><strong>Estado:</strong> {{ orden.estado }}</p>
      <p><strong>Total:</strong> Gs. {{ orden.total ? orden.total.toLocaleString() : 0 }}</p>

      <p><strong>Cliente:</strong> {{ orden.cliente?.nombre_completo || 'Sin datos de cliente' }}</p>
      <p><strong>Email:</strong> {{ orden.cliente?.email || 'Sin correo' }}</p>

      <h3>Productos:</h3>
      <ul>
        <li v-for="producto in orden.productos || []" :key="producto.id">
          {{ producto.nombre }} × {{ producto.pivot?.cantidad || 0 }} = Gs. {{ producto.pivot?.subtotal?.toLocaleString() || 0 }}
        </li>
      </ul>

      <div v-if="orden.pago">
        <h3>Pago:</h3>
        <p><strong>Estado del pago:</strong> {{ orden.pago.estado }}</p>
        <p><strong>Referencia:</strong> {{ orden.pago.referencia_pago }}</p>
        <p><strong>Confirmado en:</strong> {{ orden.pago.confirmado_en || 'Pendiente' }}</p>
        <p>
          <a :href="`https://adamspay.com/pagar/${orden.pago.referencia_pago}`" target="_blank">
            🔗 Ver link de pago
          </a>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const orden = ref(null)
const cargando = ref(true)
const error = ref('')

onMounted(async () => {
  try {
    const res = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/api/ordenes/${route.params.id}`)
    orden.value = res.data
  } catch (err) {
    console.error('Error cargando orden:', err)
    error.value = 'No se pudo cargar la orden.'
  } finally {
    cargando.value = false
  }
})
</script>

<style scoped>
.ver-orden {
  max-width: 600px;
  margin: auto;
  background: #fff;
  padding: 20px;
  border-radius: 10px;
}
h1, h2 {
  text-align: center;
}
ul {
  list-style: none;
  padding: 0;
}
li {
  margin-bottom: 8px;
}
</style>
