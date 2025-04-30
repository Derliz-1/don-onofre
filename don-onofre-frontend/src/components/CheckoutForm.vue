<template>
  <div>
    <h2>📋 Datos del Cliente</h2>

    <form @submit.prevent="realizarPedido">
      <input v-model="cliente.nombre_completo" placeholder="Nombre completo" required />
      <input v-model="cliente.email" placeholder="Email" required type="email" />
      <input v-model="cliente.telefono" placeholder="Teléfono" />
      <input v-model="cliente.direccion" placeholder="Dirección" />
      <input v-model="cliente.ciudad" placeholder="Ciudad" />
      <input v-model="cliente.pais" placeholder="País" />
      <textarea v-model="notas" placeholder="Notas del pedido..."></textarea>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Procesando...' : 'Generar Orden y Pagar' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import api from '../api'

const props = defineProps(['carrito'])

const cliente = ref({
  nombre_completo: '',
  email: '',
  telefono: '',
  direccion: '',
  ciudad: '',
  pais: ''
})
const notas = ref('')
const loading = ref(false)

const realizarPedido = async () => {
  if (props.carrito.length === 0) return

  loading.value = true

  try {
    const res = await api.post('/ordenes', {
      cliente: cliente.value,
      productos: props.carrito.map(p => ({ id: p.id, cantidad: p.cantidad })),
      notas: notas.value
    })

    const pago = await api.post(`/pagos/${res.data.orden.id}/generar`)

    // ✅ Redirige al link real de AdamsPay
    window.location.href = pago.data.link_pago

  } catch (error) {
    alert('Error al generar la orden o el link de pago')
    console.error(error)
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.checkout-form {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
input, textarea {
  margin-bottom: 10px;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  width: 100%;
}
button {
  padding: 10px;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 4px;
  font-weight: bold;
  cursor: pointer;
}
button:hover {
  background-color: #218838;
}
.error {
  color: red;
  margin-top: 10px;
}
</style>



