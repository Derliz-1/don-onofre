<template>
  <div class="product-card">
    <div class="status" :class="producto.stock > 0 ? 'disponible' : 'agotado'">
      {{ producto.stock > 0 ? 'Disponible' : 'AGOTADO' }}
    </div>

   <img :src="producto.imagen_url" alt="Producto" class="product-img" />

    <h3 class="nombre">{{ producto.nombre }}</h3>
    <p class="precio">Gs. {{ producto.precio.toLocaleString() }}</p>

    <div class="botones">
      <button @click="mostrarDescripcion = !mostrarDescripcion">
        {{ mostrarDescripcion ? '🔽 Ocultar' : '👁 Ver descripción' }}
      </button>
      <button
        @click="$emit('agregar', producto)"
        :disabled="producto.stock === 0"
      >
        ➕ Agregar
      </button>
    </div>

    <transition name="fade">
      <div v-if="mostrarDescripcion" class="descripcion">
        {{ producto.descripcion || 'Sin descripción disponible.' }}
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const props = defineProps(['producto'])
const mostrarDescripcion = ref(false)
</script>

<style scoped>
.product-card {
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  overflow: hidden;
  transition: transform 0.3s;
  position: relative;
  padding: 16px;
  width: 100%;
  max-width: 260px;
  margin: 0 auto;
  text-align: center;
}
.product-card:hover {
  transform: translateY(-5px);
}
.product-img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 10px;
}
.nombre {
  font-size: 18px;
  margin: 10px 0 6px;
}
.precio {
  font-size: 16px;
  font-weight: bold;
  color: #28a745;
  margin-bottom: 12px;
}
.botones {
  display: flex;
  gap: 8px;
  margin-top: 10px;
}
button {
  flex: 1;
  background: #0a7cff;
  color: white;
  border: none;
  padding: 8px 10px;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.3s;
  font-size: 14px;
}
button:hover {
  background: #065fc6;
}
button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
.descripcion {
  margin-top: 10px;
  background: #f8f9fa;
  padding: 10px;
  border-radius: 8px;
  font-size: 14px;
  text-align: left;
  max-height: 200px;
  overflow-y: auto;
}
.status {
  position: absolute;
  top: 10px;
  right: 10px;
  padding: 5px 10px;
  font-weight: bold;
  border-radius: 8px;
  font-size: 12px;
}
.disponible {
  background: #28a745;
  color: white;
}
.agotado {
  background: #dc3545;
  color: white;
}

/* Animación de descripción */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>