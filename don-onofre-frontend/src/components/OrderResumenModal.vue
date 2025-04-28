<template>
  <div class="modal-overlay">
    <div class="modal">
      <h2>✅ Comprobante de Orden</h2>

      <p><strong>Orden:</strong> {{ orden.id }}</p>
      <p><strong>Total:</strong> Gs. {{ orden.total }}</p>

      <div class="acciones">
        <button class="confirmar" @click="confirmar">✅ Confirmar Pago</button>
        <button class="cancelar" @click="cancelar">❌ Cancelar Pago</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import api from '../api'
import { ref } from 'vue'

const props = defineProps(['orden'])
const emits = defineEmits(['close'])
const router = useRouter()

const carrito = ref(JSON.parse(localStorage.getItem('carrito') || '[]'))

const vaciarCarrito = () => {
  carrito.value = []
  localStorage.setItem('carrito', JSON.stringify([]))
}

const confirmar = async () => {
  try {
    const referencia = props.orden.pago.referencia_pago
    const res = await api.post(`/pagos/${referencia}/confirmar-simulado`)
    console.log('Pago confirmado:', res.data)

    // 📢 Vaciar carrito después de confirmar pago exitoso
    vaciarCarrito()

    // Emitir cierre del modal
    emits('close', 'confirmado')

    router.push(`/orden/${props.orden.id}`)
  } catch (error) {
    alert('Error al confirmar el pago')
    console.error(error)
  }
}

const cancelar = async () => {
  try {
    await api.post(`/pagos/${props.orden.pago.id}/cancelar`)
    emits('close', 'cancelada')
  } catch (error) {
    console.error('Error al cancelar', error)
  }
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0; left: 0; width: 100vw; height: 100vh;
  background: rgba(0, 0, 0, 0.6);
  display: flex; align-items: center; justify-content: center;
}
.modal {
  background: white;
  padding: 20px;
  border-radius: 12px;
  max-width: 400px;
  width: 90%;
}

.acciones {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}
.confirmar {
  background-color: #28a745;
  color: white;
  padding: 10px 16px;
  border: none;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
}
.confirmar:hover {
  background-color: #218838;
}
.cancelar {
  background-color: #dc3545;
  color: white;
  padding: 10px 16px;
  border: none;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
}
.cancelar:hover {
  background-color: #c82333;
}
</style>