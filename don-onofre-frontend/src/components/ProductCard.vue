<template>
  <div class="product-card">
    <!-- Cartel de estado -->
    <div class="status" :class="producto.stock > 0 ? 'disponible' : 'agotado'">
      {{ producto.stock > 0 ? 'Disponible' : 'AGOTADO' }}
    </div>

    <img :src="producto.imagen_url" alt="producto" class="product-img" />
    <h3>{{ producto.nombre }}</h3>
    <p class="precio">Gs. {{ producto.precio }}</p>

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

    <!-- DESCRIPCIÓN EXPANDIBLE -->
    <div v-if="mostrarDescripcion" class="descripcion">
      {{ producto.descripcion || 'Sin descripción disponible.' }}
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const props = defineProps(['producto'])

const mostrarDescripcion = ref(false)
</script>

<style scoped>
.product-card {
  border: 1px solid #ccc;
  padding: 12px;
  border-radius: 8px;
  background: #fff;
  width: 240px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  text-align: center;
  transition: all 0.3s ease;
  position: relative;
}
.product-img {
  width: 100%;
  height: 160px;
  object-fit: cover;
  border-radius: 6px;
}
.precio {
  font-weight: bold;
  margin: 10px 0;
}
.botones {
  display: flex;
  justify-content: space-between;
  gap: 10px;
}
button {
  background-color: #0a7cff;
  color: white;
  border: none;
  padding: 8px 12px;
  border-radius: 4px;
  cursor: pointer;
  flex: 1;
}
button:hover {
  background-color: #065fc6;
}
button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
.descripcion {
  margin-top: 10px;
  background: #f4f4f4;
  padding: 10px;
  border-radius: 6px;
  font-size: 0.9rem;
  text-align: left;
  max-height: 200px;
  overflow-y: auto;
}

/* Estilos del cartel de estado */
.status {
  position: absolute;
  top: 8px;
  right: 8px;
  padding: 4px 8px;
  font-weight: bold;
  border-radius: 6px;
  font-size: 0.8rem;
}
.disponible {
  background-color: #28a745;
  color: white;
}
.agotado {
  background-color: #dc3545;
  color: white;
}
</style>
