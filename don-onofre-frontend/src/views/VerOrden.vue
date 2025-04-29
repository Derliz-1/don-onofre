<template>
  <div class="ver-orden" ref="comprobante">
    <h1>🧾 Orden #{{ orden.id }}</h1>

    <div v-if="cargando">Cargando orden...</div>
    <div v-else-if="error">{{ error }}</div>
    <div v-else>
      <p><strong>Estado:</strong> {{ orden.estado }}</p>
      <p><strong>Total:</strong> Gs. {{ orden.total.toLocaleString() }}</p>
      
      <h2>📋 Cliente</h2>
      <p><strong>Nombre:</strong> {{ orden.cliente?.nombre }} {{ orden.cliente?.apellido }}</p>
      <p><strong>Email:</strong> {{ orden.cliente?.email }}</p>

      <h2>🛒 Productos</h2>
      <ul>
        <li v-for="producto in orden.productos" :key="producto.id">
          {{ producto.nombre }} × {{ producto.pivot.cantidad }} 
          = Gs. {{ producto.pivot.subtotal.toLocaleString() }}
        </li>
      </ul>

      <div v-if="orden.pago">
        <h2>💳 Pago</h2>
        <p><strong>Estado del pago:</strong> {{ orden.pago.estado }}</p>
        <p><strong>Referencia:</strong> {{ orden.pago.referencia_pago }}</p>
        <p><strong>Confirmado en:</strong> {{ orden.pago.confirmado_en ?? 'Pendiente' }}</p>
        <p>
          <a :href="'https://adamspay.com/pagar/' + orden.pago.referencia_pago" target="_blank">
            🔗 Ver link de pago
          </a>
        </p>
      </div>

      <!-- BOTONES -->
      <div class="botones">
        <button @click="imprimir">🖨️ Imprimir Comprobante</button>
        <button @click="compartirWhatsapp">📤 Compartir por WhatsApp</button>
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
const comprobante = ref(null) // Para referenciar el div del comprobante

onMounted(async () => {
  try {
    const res = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/api/ordenes/${route.params.id}`)
    orden.value = res.data
  } catch (err) {
    error.value = 'No se pudo cargar la orden.'
  } finally {
    cargando.value = false
  }
})

const imprimir = () => {
  const contenido = comprobante.value.innerHTML
  const ventana = window.open('', '', 'height=600,width=400')
  ventana.document.write('<html><head><title>Comprobante de Orden</title></head><body>')
  ventana.document.write(contenido)
  ventana.document.write('</body></html>')
  ventana.document.close()
  ventana.print()
}

const compartirWhatsapp = () => {
  if (orden.value?.pago?.referencia_pago) {
    const linkPago = `https://adamspay.com/pagar/${orden.value.pago.referencia_pago}`
    const mensaje = `Hola! Te comparto el comprobante de tu orden #${orden.value.id}. Podés pagar o ver el estado aquí: ${linkPago}`
    const url = `https://api.whatsapp.com/send?text=${encodeURIComponent(mensaje)}`
    window.open(url, '_blank')
  } else {
    alert('No hay link de pago disponible.')
  }
}
</script>

<style scoped>
.ver-orden {
  max-width: 500px;
  margin: 20px auto;
  background: #ffffff;
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
  font-size: 14px;
}
h1, h2 {
  text-align: center;
  font-size: 18px;
}
p {
  margin: 5px 0;
}
ul {
  list-style: none;
  padding: 0;
}
li {
  margin-bottom: 5px;
}
a {
  color: #007bff;
  text-decoration: underline;
}
.botones {
  margin-top: 20px;
  display: flex;
  justify-content: space-around;
}
button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 8px 12px;
  border-radius: 5px;
  cursor: pointer;
}
button:hover {
  background-color: #0056b3;
}
</style>
