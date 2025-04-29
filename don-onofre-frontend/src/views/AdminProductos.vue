<template>
  <div class="admin-productos">
    <AdminNav />
    <h2 class="titulo">📄 Gestión de Productos</h2>

    <div class="buscador">
      <input
        type="text"
        v-model="busqueda"
        placeholder="🔍 Buscar producto por nombre..."
      />
    </div>

    <form @submit.prevent="guardarProducto" class="formulario">
      <div class="grupo">
        <label>Nombre:</label>
        <input v-model="form.nombre" type="text" required />
      </div>

      <div class="grupo">
        <label>Descripción:</label>
        <input v-model="form.descripcion" type="text" />
      </div>

      <div class="grupo">
        <label>Precio:</label>
        <input v-model.number="form.precio" type="number" required />
      </div>

      <div class="grupo">
        <label>Stock:</label>
        <input v-model.number="form.stock" type="number" required />
      </div>

      <div class="grupo">
        <label>Imagen:</label>
        <input type="file" @change="handleFileUpload" accept="image/*" />
      </div>

      <div v-if="form.imagen_url" class="preview-imagen">
        <img :src="form.imagen_url" alt="Preview" />
      </div>

      <div class="grupo-checkbox">
        <label><input type="checkbox" v-model="form.activo" /> Activo</label>
        <label><input type="checkbox" v-model="form.disponible" /> Disponible</label>
        <label><input type="checkbox" v-model="form.agotado" /> Agotado</label>
      </div>

      <div class="botones-formulario">
        <button type="submit">{{ editando ? 'Actualizar' : 'Crear' }}</button>
        <button v-if="editando" type="button" @click="cancelarEdicion" class="cancelar">Cancelar</button>
      </div>
    </form>

    <div v-if="errores.length" class="errores">
      <p v-for="error in errores" :key="error" class="error">{{ error }}</p>
    </div>

    <ul class="lista-productos">
      <li v-for="producto in productosFiltrados" :key="producto.id">
        <strong>{{ producto.nombre }}</strong> - Gs. {{ producto.precio.toLocaleString() }} - Stock: {{ producto.stock }}
        <span :class="{ activo: producto.activo, inactivo: !producto.activo }">
          {{ producto.activo ? 'Activo' : 'Inactivo' }}
        </span>
        <div class="acciones">
          <button @click="toggleActivo(producto)">
            {{ producto.activo ? 'Desactivar' : 'Activar' }}
          </button>
          <button @click="cargarProducto(producto)">✏ Editar</button>
        </div>
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
    productos.value = res.data.productos || []
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
  max-width: 800px;
  margin: 30px auto;
  padding: 20px;
  background: #ffffff;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.titulo {
  text-align: center;
  margin-bottom: 20px;
  color: #007bff;
}
.buscador {
  margin-bottom: 20px;
}
.buscador input {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #ccc;
}
.formulario {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 30px;
}
.grupo {
  display: flex;
  flex-direction: column;
}
.grupo input[type="text"],
.grupo input[type="number"],
.grupo input[type="file"] {
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
}
.grupo-checkbox {
  display: flex;
  gap: 10px;
}
.preview-imagen {
  margin-top: 10px;
}
.preview-imagen img {
  width: 100px;
  height: auto;
  border-radius: 8px;
}
.botones-formulario {
  display: flex;
  gap: 10px;
}
button {
  padding: 10px 15px;
  border: none;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
}
button[type="submit"] {
  background: #28a745;
  color: white;
}
button.cancelar {
  background: #dc3545;
  color: white;
}
.lista-productos {
  list-style: none;
  padding: 0;
}
.lista-productos li {
  background: #f8f9fa;
  margin-bottom: 10px;
  padding: 12px;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.lista-productos .activo {
  color: green;
  font-weight: bold;
}
.lista-productos .inactivo {
  color: red;
  font-weight: bold;
}
.acciones {
  margin-top: 10px;
  display: flex;
  gap: 10px;
}
.errores {
  margin-top: 20px;
  color: red;
}
.error {
  font-size: 14px;
}
</style>