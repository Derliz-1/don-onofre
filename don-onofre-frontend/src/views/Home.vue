<template>
  <div class="layout">
    <header class="header">
      <div class="logo">🛒 Don Onofre</div>
      <div class="buscador-carrito">
        <input type="text" v-model="busqueda" placeholder="Buscar productos..." />
        <button class="btn-carrito" @click="mostrarModal = true">
          🛍️ Ver Carrito ({{ totalItems }})
        </button>
      </div>
    </header>

    <div class="main">
      <aside class="sidebar">
        <h3>Categorías</h3>
        <ul>
          <li :class="{ active: categoriaSeleccionada === '' }" @click="categoriaSeleccionada = ''">Todas</li>
          <li
            v-for="cat in categorias"
            :key="cat"
            :class="{ active: categoriaSeleccionada === cat }"
            @click="categoriaSeleccionada = cat"
          >
            {{ cat }}
          </li>
        </ul>
      </aside>

      <section class="productos">
        <ProductCard
          v-for="producto in productosFiltrados"
          :key="producto.id"
          :producto="producto"
          @agregar="agregarAlCarrito"
        />
      </section>
    </div>

    <button v-if="!fin" @click="cargarProductos" class="ver-mas">Ver más productos</button>
    <button v-if="pagina > 2" @click="reiniciarProductos" class="ver-menos">Ver menos productos</button>

    <!-- MODAL de carrito y checkout -->
    <FloatingCart
      v-if="mostrarModal"
      :carrito="carrito"
      :total="total"
      @cerrar="handleCerrarModal"
      @vaciarCarrito="vaciarCarrito"
      @eliminar="eliminarDelCarrito"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../api.js'
import ProductCard from '../components/ProductCard.vue'
import FloatingCart from '../components/FloatingCart.vue' // ✅ mantiene el flujo correcto

const productos = ref([])
const pagina = ref(1)
const perPage = 6
const cargando = ref(false)
const fin = ref(false)
const carrito = ref([])
const busqueda = ref('')
const categoriaSeleccionada = ref('')
const mostrarModal = ref(false)

const categorias = computed(() => {
  const todas = productos.value.map(p => p.categoria)
  return [...new Set(todas.filter(Boolean))]
})

const total = computed(() => carrito.value.reduce((suma, item) => suma + item.precio * item.cantidad, 0))
const totalItems = computed(() => carrito.value.reduce((n, item) => n + item.cantidad, 0))

const productosFiltrados = computed(() => {
  return productos.value.filter(p => {
    const matchCategoria = categoriaSeleccionada.value === '' || p.categoria === categoriaSeleccionada.value
    const matchBusqueda = p.nombre.toLowerCase().includes(busqueda.value.toLowerCase())
    return matchCategoria && matchBusqueda
  })
})

const agregarAlCarrito = (producto) => {
  if (producto.stock > 0) {
    const item = carrito.value.find(p => p.id === producto.id)
    if (item) {
      item.cantidad++
    } else {
      carrito.value.push({ ...producto, cantidad: 1 })
    }
    const prod = productos.value.find(p => p.id === producto.id)
    if (prod) {
      prod.stock--
    }
    guardarCarrito()
  }
}

const eliminarDelCarrito = (productoId) => {
  const item = carrito.value.find(p => p.id === productoId)
  if (item) {
    const prod = productos.value.find(p => p.id === productoId)
    if (prod) {
      prod.stock += item.cantidad
    }
  }
  carrito.value = carrito.value.filter(p => p.id !== productoId)
  guardarCarrito()
}

const vaciarCarrito = () => {
  carrito.value = []
  guardarCarrito()
}

const handleCerrarModal = () => {
  mostrarModal.value = false
}

const reiniciarProductos = async () => {
  productos.value = []
  pagina.value = 1
  fin.value = false
  await cargarProductos()
}

const cargarProductos = async () => {
  if (cargando.value || fin.value) return

  cargando.value = true
  try {
    const res = await api.get('/productos', {
      params: { page: pagina.value, per_page: perPage }
    })

    if ((res.data.data || []).length === 0) {
      fin.value = true
    } else {
      productos.value.push(...res.data.data)
      pagina.value++
    }
  } catch (error) {
    console.error('Error cargando productos:', error)
  }

  cargando.value = false
}

const guardarCarrito = () => {
  localStorage.setItem('carrito', JSON.stringify(carrito.value))
}

const recuperarCarrito = () => {
  const guardado = localStorage.getItem('carrito')
  if (guardado) {
    carrito.value = JSON.parse(guardado)
  }
}

onMounted(() => {
  recuperarCarrito()
  cargarProductos()
})
</script>

<style scoped>
.layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.header {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  background-color: #f8f9fa;
  padding: 10px 20px;
}
.logo {
  font-size: 1.8rem;
  font-weight: bold;
  color: #007bff;
}
.buscador-carrito {
  display: flex;
  flex: 1;
  flex-wrap: wrap;
  gap: 10px;
  align-items: center;
  margin-top: 10px;
}
.buscador-carrito input {
  padding: 8px 12px;
  font-size: 1rem;
  flex: 1;
  min-width: 220px;
  border: 1px solid #ccc;
  border-radius: 6px;
}
.btn-carrito {
  background-color: #28a745;
  color: white;
  padding: 10px 14px;
  border: none;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
}
.btn-carrito:hover {
  background-color: #218838;
}

.main {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin: 20px;
}

.sidebar {
  flex: 1 1 200px;
  background: #f1f3f5;
  padding: 20px;
  border-radius: 10px;
}
.sidebar h3 {
  margin-bottom: 10px;
}
.sidebar ul {
  list-style: none;
  padding: 0;
}
.sidebar li {
  margin-bottom: 10px;
  cursor: pointer;
  padding: 6px 10px;
  border-radius: 6px;
}
.sidebar li:hover {
  background: #e9ecef;
}
.sidebar .active {
  font-weight: bold;
  color: #007bff;
  background: #dee2e6;
}

.productos {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  flex-grow: 1;
}

.ver-mas, .ver-menos {
  margin: 20px auto;
  padding: 10px 20px;
  font-weight: bold;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
.ver-mas:hover {
  background-color: #0056b3;
}
.ver-menos {
  background-color: #dc3545;
}
.ver-menos:hover {
  background-color: #bd2130;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .header {
    flex-direction: column;
    align-items: flex-start;
  }
  .buscador-carrito {
    width: 100%;
    margin-top: 10px;
    justify-content: flex-start;
  }
  .main {
    flex-direction: column;
  }
  .sidebar {
    width: 100%;
    margin-bottom: 20px;
  }
  .productos {
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  }
}
</style>