<script setup>
import { usersInformationSession } from '../../store/usersStore.js'
import AppLayout from '../../layouts/AppLayout.vue'
import Axios from 'axios'
import { onMounted, ref } from 'vue'
import Swal from 'sweetalert2'

const axios = Axios.create({
  baseURL: import.meta.env.VITE_PUBLIC_BACKEND_URL,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
})

const storeSession = usersInformationSession()
const firstname = ref('')
const lastname = ref('')
const email = ref('')
const phone = ref('')

onMounted(() => {
  firstname.value = storeSession.loggedUser.firstname
  lastname.value = storeSession.loggedUser.lastname
  email.value = storeSession.loggedUser.email
  phone.value = storeSession.loggedUser.phone
})

async function updateUserInfo() {
  var selectedClientData = {
    firstname: firstname.value,
    lastname: lastname.value,
    email: email.value,
    phone: phone.value,
    _id: storeSession.loggedUser._id,
    amount: storeSession.loggedUser.amount,
  }

  await axios
    .put(import.meta.env.VITE_PUBLIC_BACKEND_URL + '/clients/update/', selectedClientData)
    .then((response) => {
      if (typeof response.status != 'undefined' && response.status == '201') {
        Swal.fire({
          icon: 'success',
          title: '¡Actualizado!',
          text: 'Se ha actualizado tu información',
        })
      }
    })
    .catch((error) => {
      if (error.response.status !== 422 && error.response.status !== 401) {
        throw error
      } else {
        ElNotification({
          title: 'Error',
          message: error.response.statusText,
          type: 'error',
        })
      }
    })
}
</script>

<template>
  <div>
    <AppLayout>
      <div class="container mt-5">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Perfil de Usuario</h5>
          </div>
          <div class="card-body">
            <form>
              <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" v-model="firstname" class="form-control" id="nombre" placeholder="Ingresa tu nombre" />
              </div>
              <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" v-model="lastname" class="form-control" id="apellido" placeholder="Ingresa tu apellido" />
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" v-model="email" class="form-control" id="email" placeholder="Ingresa tu correo electrónico" />
              </div>
              <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" v-model="phone" class="form-control" id="telefono" placeholder="Ingresa tu número de teléfono" />
              </div>
              <el-button type="primary" @click="updateUserInfo()">Guardar</el-button>
            </form>
          </div>
        </div>
      </div>
    </AppLayout>
  </div>
</template>


<style>
</style>