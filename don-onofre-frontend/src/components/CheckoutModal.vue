<template>
  <div class="modal-overlay" @click.self="cerrar">
    <div class="modal">
      <h2>🛒 Carrito y Datos del Cliente</h2>

      <div class="carrito">
        <ul>
          <li v-for="item in carrito" :key="item.id">
            {{ item.nombre }} × {{ item.cantidad }} = Gs. {{ item.precio * item.cantidad }}
            <button @click="$emit('eliminar', item.id)">❌</button>
          </li>
        </ul>
        <p><strong>Total:</strong> Gs. {{ total }}</p>
      </div>

      <CheckoutForm :carrito="carrito" />
    </div>
  </div>
</template>

<script setup>
import CheckoutForm from './CheckoutForm.vue'
defineProps(['carrito', 'total'])
const emit = defineEmits(['cerrar', 'eliminar'])

const cerrar = () => emit('cerrar')
</script>