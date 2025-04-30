<template>
  <div class="modal-overlay">
    <div class="modal-content">
      <h3>🛒 Tu carrito</h3>

      <ul>
        <li v-for="item in carrito" :key="item.id">
          {{ item.nombre }} x {{ item.cantidad }} = Gs. {{ item.precio * item.cantidad }}
          <button @click="$emit('eliminar', item.id)">❌</button>
        </li>
      </ul>

      <p><strong>Total:</strong> Gs. {{ total }}</p>

      <CheckoutForm :carrito="carrito" />
    </div>
  </div>
</template>

<script setup>
import CheckoutForm from './CheckoutForm.vue'
defineProps(['carrito', 'total'])
defineEmits(['cerrar', 'eliminar']) // ya no necesitás vaciarCarrito aquí
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}
.modal-content {
  background: white;
  padding: 20px;
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
}
</style>