<script setup>
import { reactive } from 'vue'
import { ElNotification } from 'element-plus'
import { useRouter } from 'vue-router'
import Axios from 'axios'

const { push } = useRouter()
const form = reactive({
  firstname: '',
  lastname: '',
  email: '',
  phone: '',
  password: '',
})

const axios = Axios.create({
  baseURL: import.meta.env.VITE_PUBLIC_BACKEND_URL,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
})

async function createNewUser() {
  try {
    const csrf = () => axios.get(import.meta.env.VITE_PUBLIC_BACKEND_URL + '/sanctum/csrf-cookie')
    await csrf()

    axios
      .post(import.meta.env.VITE_PUBLIC_BACKEND_URL + '/register', form)
      .then((response) => {
        if (typeof response.status != 'undefined' && (response.status == '200' || response.status == 204 || response.status == 201)) {
          ElNotification({
            title: 'Bienvenido',
            message: 'Bienvenido',
            type: 'success',
          })

          push({ path: '/login' })
        }
      })
      .catch((error) => {
        console.log(error)
        if (error.response.status !== 422) {
          throw error
        } else {
          ElNotification({
            title: 'Error',
            message: error.response.data.errors.email[0],
            type: 'error',
          })
        }
      })
  } catch (error) {}
}
</script>

<template>
  <body>
    <div id="app">
      <div class="login-container">
        <h1>Registrarse</h1>
        <el-form label-position="top" class="mt-5">
          <el-form-item label="Nombre:">
            <el-input placeholder="Nombre" type="text" v-model="form.firstname" required />
          </el-form-item>
          <el-form-item label="Apellido:">
            <el-input placeholder="Apellido" type="text" v-model="form.lastname" required />
          </el-form-item>
          <el-form-item label="Correo:">
            <el-input placeholder="Correo" type="email" v-model="form.email" required />
          </el-form-item>
          <el-form-item label="Telefono:">
            <el-input-number controls-position="right" style="width: 100%" placeholder="Correo" type="number" v-model="form.phone" required />
          </el-form-item>
          <el-form-item label="Contraseña:">
            <el-input placeholder="Contraseña" type="password" id="password" v-model="form.password" required />
          </el-form-item>
          <el-button type="primary" @click="createNewUser">Registrarse</el-button>
        </el-form>
        <h6 class="mt-5">
          ¿Ya eres parte de la familia?
          <a href="/#/login">Inicia sesión aquí</a>
        </h6>
      </div>
    </div>
  </body>
</template>

<style scoped>
body {
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #191b27; /* Fondo gris */
}

.login-container {
  text-align: center;
  background-color: #fff;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 5px;
  width: 400px;
  height: 700px;
}

form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

button {
  margin-top: 15px;
}
</style>
