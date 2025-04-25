<template>
  <div class="cart">
    <h2>🛒 Carrito</h2>

    <div v-if="carrito.length === 0">
      <p>Tu carrito está vacío.</p>
    </div>

    <ul v-else>
      <li v-for="(item, index) in carrito" :key="index">
        <strong>{{ item.nombre }}</strong> × {{ item.cantidad }} =
        Gs. {{ item.precio * item.cantidad }}
        <button @click="$emit('eliminar', item.id)">❌</button>
      </li>
    </ul>

    <div v-if="carrito.length > 0" class="total">
      <p><strong>Total:</strong> Gs. {{ total }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps(['carrito', 'total'])
</script>

<style scoped>
.cart {
  background-color: #fff;
  padding: 16px;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  margin-top: 20px;
}
ul {
  list-style: none;
  padding: 0;
}
li {
  margin-bottom: 10px;
}
button {
  background: transparent;
  border: none;
  color: red;
  cursor: pointer;
  font-size: 1rem;
}
.total {
  margin-top: 16px;
  font-size: 1.2rem;
}
</style>
