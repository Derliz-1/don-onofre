<template>
  <div class="login">
    <h2>🔐 Iniciar sesión como Administrador</h2>
    <form @submit.prevent="login">
      <input v-model="email" type="email" placeholder="Correo electrónico" required />
      <input v-model="password" type="password" placeholder="Contraseña" required />
      <button>Ingresar</button>
    </form>
    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

// ✅ Creamos instancia de axios usando VITE_API_URL
const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    'Content-Type': 'application/json'
  }
})

const email = ref('')
const password = ref('')
const error = ref('')
const router = useRouter()

const login = async () => {
  try {
    const res = await api.post('/login', { email: email.value, password: password.value })
    localStorage.setItem('admin_token', res.data.token)
    router.push('/admin')
  } catch (err) {
    error.value = 'Credenciales inválidas o no autorizado'
  }
}
</script>

<style scoped>
.login {
  max-width: 400px;
  margin: 80px auto;
  background: #fff;
  padding: 20px;
  border-radius: 8px;
}
input {
  display: block;
  margin-bottom: 10px;
  padding: 10px;
  width: 100%;
}
button {
  width: 100%;
  background: #333;
  color: white;
  padding: 10px;
  border: none;
}
.error {
  color: red;
  margin-top: 10px;
}
</style>
