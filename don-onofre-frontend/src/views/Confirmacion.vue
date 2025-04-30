<template>
  <div class="confirmacion">
    <h1>💬 Estado del Pago</h1>

    <div v-if="cargando" class="estado cargando">⏳ Verificando estado...</div>
    <div v-else-if="error" class="estado error">❌ {{ error }}</div>
    <div v-else-if="estado === 'pagado'" class="estado pagado">✅ ¡Pago exitoso!</div>
    <div v-else-if="estado === 'pendiente'" class="estado pendiente">🕑 Pago pendiente</div>
    <div v-else-if="estado === 'cancelado'" class="estado cancelado">❌ Pago cancelado</div>

    <div class="acciones">
      <RouterLink to="/" class="btn">⬅️ Volver a Inicio</RouterLink>
      <RouterLink :to="`/orden/${ordenId}`" class="btn secundaria">🧾 Ver Comprobante</RouterLink>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const ordenId = route.params.id
const estado = ref('')
const cargando = ref(true)
const error = ref('')

onMounted(async () => {
  try {
    const res = await axios.get(`${import.meta.env.VITE_API_URL}/pagos/${ordenId}`)
    estado.value = res.data.estado_pago
  } catch (err) {
    console.error(err)
    error.value = 'No se pudo verificar el estado del pago.'
  } finally {
    cargando.value = false
  }
})
</script>

<style scoped>
.confirmacion {
  max-width: 500px;
  margin: auto;
  padding: 30px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
  text-align: center;
}
.estado {
  font-size: 1.4rem;
  font-weight: bold;
  margin: 20px 0;
}
.pagado {
  color: green;
}
.pendiente {
  color: orange;
}
.cancelado {
  color: red;
}
.error {
  color: crimson;
}
.cargando {
  color: #555;
}
.acciones {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 30px;
}
.btn {
  padding: 10px;
  font-weight: bold;
  text-decoration: none;
  color: white;
  background-color: #007bff;
  border-radius: 8px;
}
.btn:hover {
  background-color: #0056b3;
}
.secundaria {
  background-color: #6c757d;
}
.secundaria:hover {
  background-color: #5a6268;
}
</style>