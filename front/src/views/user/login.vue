<script setup>
import { ref } from 'vue'
import { ElNotification } from 'element-plus'
import { useRouter } from 'vue-router'
import logo from '../../assets/media/logos/logo-light.png'
import Axios from 'axios'

const { push } = useRouter()

const username = ref('')
const password = ref('')

const axios = Axios.create({
  baseURL: import.meta.env.VITE_PUBLIC_BACKEND_URL,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
})

async function sendLogIn() {
  const csrf = () => axios.get(import.meta.env.VITE_PUBLIC_BACKEND_URL + '/sanctum/csrf-cookie')
  await csrf()

  const dataSend = ref({ email: username.value, password: password.value, remember: false })

  axios
    .post(import.meta.env.VITE_PUBLIC_BACKEND_URL + '/login', dataSend.value)
    .then((response) => {
      if (typeof response.status != 'undefined' && (response.status == '200' || response.status == 204)) {
        ElNotification({
          title: 'Bienvenido',
          message: 'Bienvenido',
          type: 'success',
        })

        push({ path: '/' })
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
}
</script>

<template>
  <body>
    <div id="app">
      <div class="logo">
        <a href="demo1/index.html">
          <img alt="Logo" :src="logo" style="width: 250px" />
        </a>
      </div>
      <div class="login-container mt-5">
        <h1>Iniciar Sesión</h1>
        <el-form label-position="top" class="mt-5">
          <el-form-item label="Usuario:">
            <el-input placeholder="Usuario" type="text" id="username" v-model="username" required />
          </el-form-item>

          <el-form-item label="Contraseña:">
            <el-input placeholder="Contraseña" type="password" id="password" v-model="password" required />
          </el-form-item>

          <el-button type="primary" @click="sendLogIn()">Iniciar sesión</el-button>
        </el-form>
        <h6 class="mt-5">
          ¿Aún no eres parte de la familia?
          <a href="/#/register">Registrate aquí</a>
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
  background: hsla(39, 53%, 55%, 1);
  background: radial-gradient(circle, hsla(39, 53%, 55%, 1) 0%, hsla(38, 38%, 28%, 1) 39%, hsla(0, 0%, 7%, 1) 100%);
  background: -moz-radial-gradient(circle, hsla(39, 53%, 55%, 1) 0%, hsla(38, 38%, 28%, 1) 39%, hsla(0, 0%, 7%, 1) 100%);
  background: -webkit-radial-gradient(circle, hsla(39, 53%, 55%, 1) 0%, hsla(38, 38%, 28%, 1) 39%, hsla(0, 0%, 7%, 1) 100%);
  filter: progid: DXImageTransform.Microsoft.gradient( startColorstr="#C99E50", endColorstr="#64502D", GradientType=1 );
}

.login-container {
  text-align: center;
  background-color: #fff;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 5px;
  width: 400px;
  height: 420px;
}

.logo {
  text-align: center;
  background-color: #fff;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 5px;
  width: 400px;
  height: 180px;
}

form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

button {
  margin-top: 15px;
  background-color: #c99e50;
  border-color: #c99e50;
}

button:hover {
  margin-top: 15px;
  background-color: #fcb32d;
  border-color: #fcb32d;
}
</style>
