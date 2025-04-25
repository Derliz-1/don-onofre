<template>
  <div class="admin-productos">
    <AdminNav />
    <h2>📄 Productos</h2>

    <input type="text" v-model="busqueda" placeholder="🔍 Buscar producto por nombre..." />

    <!-- Formulario de creación/edición -->
    <form @submit.prevent="guardarProducto">
      <label>
        Nombre:
        <input v-model="form.nombre" type="text" required />
      </label>

      <label>
        Descripción:
        <input v-model="form.descripcion" type="text" />
      </label>

      <label>
        Precio:
        <input v-model.number="form.precio" type="number" required />
      </label>

      <label>
        Stock:
        <input v-model.number="form.stock" type="number" required />
      </label>

      <label>
        Imagen:
        <input type="file" @change="handleFileUpload" accept="image/*" />
      </label>

      <div v-if="form.imagen_url">
        <img :src="form.imagen_url" alt="Preview" width="100" style="margin-top: 10px" />
      </div>

      <label><input type="checkbox" v-model="form.activo" /> Activo</label>
      <label><input type="checkbox" v-model="form.disponible" /> Disponible</label>
      <label><input type="checkbox" v-model="form.agotado" /> Agotado</label>

      <button type="submit">{{ editando ? 'Actualizar' : 'Crear' }}</button>
      <button v-if="editando" type="button" @click="cancelarEdicion">Cancelar</button>
    </form>

    <div v-if="errores.length" class="errores">
      <p v-for="error in errores" :key="error" class="error">{{ error }}</p>
    </div>

    <ul>
      <li v-for="producto in productosFiltrados" :key="producto.id">
        <strong>{{ producto.nombre }}</strong> - Gs. {{ producto.precio }} - Stock: {{ producto.stock }} -
        <span :style="{ color: producto.activo ? 'green' : 'red' }">
          {{ producto.activo ? 'Activo' : 'Inactivo' }}
        </span>

        <!-- Botones -->
        <button @click="toggleActivo(producto)">
          {{ producto.activo ? 'Desactivar' : 'Activar' }}
        </button>

        <button @click="cargarProducto(producto)">✏ Editar</button>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../api/admin'

const productos = ref([])
const errores = ref([])
const editando = ref(false)
const busqueda = ref('')
let productoEditadoId = null

const form = ref({
  nombre: '',
  descripcion: '',
  precio: 0,
  stock: 0,
  imagen_url: '',
  disponible: true,
  activo: true,
  agotado: false
})

const cargarProductos = async () => {
  try {
    const res = await api.get('/productos?admin=true')
    productos.value = (res.data.productos || []) // Fijate que ahora es res.data.productos
  } catch (err) {
    errores.value = ['Error al cargar productos']
    console.error(err)
  }
}

const productosFiltrados = computed(() =>
  productos.value.filter(p =>
    p.nombre.toLowerCase().includes(busqueda.value.toLowerCase())
  )
)

const guardarProducto = async () => {
  errores.value = []
  try {
    if (editando.value) {
      await api.put(`/productos/${productoEditadoId}`, form.value)
    } else {
      await api.post('/productos', form.value)
    }
    await cargarProductos()
    cancelarEdicion()
  } catch (err) {
    if (err.response?.status === 422) {
      errores.value = Object.values(err.response.data.errors).flat()
    } else {
      errores.value = ['Error inesperado al guardar producto']
    }
  }
}

const cargarProducto = (producto) => {
  form.value = {
    nombre: producto.nombre || '',
    descripcion: producto.descripcion || '',
    precio: producto.precio || 0,
    stock: producto.stock || 0,
    imagen_url: producto.imagen_url || '',
    disponible: producto.disponible ?? true,
    activo: producto.activo ?? true,
    agotado: producto.agotado ?? false
  }
  editando.value = true
  productoEditadoId = producto.id
}

const cancelarEdicion = () => {
  form.value = {
    nombre: '',
    descripcion: '',
    precio: 0,
    stock: 0,
    imagen_url: '',
    disponible: true,
    activo: true,
    agotado: false
  }
  editando.value = false
  productoEditadoId = null
  errores.value = []
}

const toggleActivo = async (producto) => {
  try {
    await api.put(`/productos/${producto.id}`, {
      ...producto,
      activo: !producto.activo
    })
    await cargarProductos()
  } catch (error) {
    errores.value = ['Error al cambiar estado del producto']
    console.error(error)
  }
}

const handleFileUpload = async (event) => {
  const file = event.target.files[0]
  if (!file) return

  const formData = new FormData()
  formData.append('imagen', file)

  try {
    const res = await api.post('/productos/upload-imagen', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    form.value.imagen_url = res.data.url
  } catch (err) {
    errores.value = ['Error al subir la imagen']
    console.error(err)
  }
}

onMounted(cargarProductos)
</script>

<style scoped>
.admin-productos {
  max-width: 700px;
  margin: auto;
}
form {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 20px;
}
input[type='checkbox'] {
  margin-right: 6px;
}
.errores {
  margin-top: 10px;
  color: red;
}
.error {
  font-size: 14px;
}
ul {
  list-style: none;
  padding-left: 0;
}
li {
  border-bottom: 1px solid #ccc;
  padding: 10px 0;
}
button {
  margin-left: 8px;
}
input[type='text'],
input[type='number'] {
  margin-top: 4px;
  margin-bottom: 8px;
  padding: 6px 8px;
  width: 100%;
}
</style>