<template>
  <div class="comprobante">
    <h1>🧾 Comprobante de Orden</h1>

    <div v-if="cargando" class="loading">⏳ Cargando comprobante...</div>
    <div v-else-if="error" class="error">❌ {{ error }}</div>
    <div v-else-if="orden">
      <div class="info">
        <p><strong>Orden #:</strong> {{ orden.id }}</p>
        <p><strong>Estado:</strong> {{ estadoTexto }}</p>
        <p><strong>Total:</strong> Gs. {{ orden.total ? orden.total.toLocaleString() : 0 }}</p>
        <p><strong>Cliente:</strong> {{ orden.cliente?.nombre_completo || 'Sin datos' }}</p>
        <p><strong>Email:</strong> {{ orden.cliente?.email || 'No disponible' }}</p>
      </div>

      <h3>🛍️ Productos:</h3>
      <ul class="productos">
        <li v-for="producto in orden.productos || []" :key="producto.id">
          {{ producto.nombre }} × {{ producto.pivot?.cantidad || 0 }} = Gs. {{ producto.pivot?.subtotal?.toLocaleString() || 0 }}
        </li>
      </ul>

      <div v-if="orden.pago" class="pago">
        <h3>💳 Datos de Pago:</h3>
        <p><strong>Referencia:</strong> {{ orden.pago.referencia_pago }}</p>
        <p><strong>Confirmado:</strong> {{ orden.pago.confirmado_en || 'Pendiente' }}</p>

        <div class="qr">
          <img :src="qrUrl" alt="QR de pago" />
          <p><a :href="`https://adamspay.com/pagar/${orden.pago.referencia_pago}`" target="_blank">🔗 Ver link de pago</a></p>
        </div>
      </div>

      <div class="acciones">
        <button class="imprimir" @click="imprimirComprobante">🖨️ Imprimir Comprobante</button>
        <button class="whatsapp" @click="compartirWhatsapp">📱 Compartir por WhatsApp</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const orden = ref(null)
const cargando = ref(true)
const error = ref('')

const estadoTexto = computed(() => {
  if (!orden.value) return ''
  switch (orden.value.estado) {
    case 'pendiente': return '🕑 Pendiente de Pago'
    case 'pagado': return '✅ Pagado'
    case 'cancelado': return '❌ Cancelado'
    default: return orden.value.estado
  }
})

const qrUrl = computed(() => {
  if (orden.value?.pago?.referencia_pago) {
    return `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://adamspay.com/pagar/${orden.value.pago.referencia_pago}`
  }
  return ''
})

const imprimirComprobante = () => window.print()

const compartirWhatsapp = () => {
  if (orden.value?.pago?.referencia_pago) {
    const link = `https://adamspay.com/pagar/${orden.value.pago.referencia_pago}`
    const texto = encodeURIComponent(`Hola, te envío el comprobante de la orden #${orden.value.id}. Link de pago: ${link}`)
    window.open(`https://wa.me/?text=${texto}`, '_blank')
  }
}

onMounted(async () => {
  try {
    const res = await axios.get(`${import.meta.env.VITE_API_URL}/ordenes/${route.params.id}`)
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
.comprobante {
  max-width: 650px;
  margin: auto;
  background: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.loading, .error {
  text-align: center;
  margin-top: 20px;
}
.info, .pago {
  margin-bottom: 20px;
}
h1, h3 {
  text-align: center;
  color: #2c3e50;
}
.productos {
  list-style: none;
  padding: 0;
}
.productos li {
  background: #f8f9fa;
  margin-bottom: 8px;
  padding: 10px;
  border-radius: 6px;
}
.qr {
  text-align: center;
  margin-top: 10px;
}
.qr img {
  width: 150px;
  height: 150px;
}
.acciones {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 20px;
}
.imprimir, .whatsapp {
  padding: 10px;
  font-weight: bold;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  color: white;
}
.imprimir {
  background-color: #007bff;
}
.imprimir:hover {
  background-color: #0056b3;
}
.whatsapp {
  background-color: #25D366;
}
.whatsapp:hover {
  background-color: #1da851;
}
</style>
