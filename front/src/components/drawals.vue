<script setup>
import { ref } from 'vue'
import { ElMessage } from 'element-plus'
import Swal from 'sweetalert2'
import Axios from 'axios'
import { ElLoading } from 'element-plus'
import { usersInformationSession } from '../store/usersStore.js'

const storeSession = usersInformationSession()
const withDrawals = ref(false)
const withDrawalsValue = ref(0)
const axios = Axios.create({
  baseURL: import.meta.env.VITE_PUBLIC_BACKEND_URL,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
})

function submitWithdrawal() {
  if (withDrawalsValue.value >= 10) {
    // Realizar la acción de envío o procesamiento del retiro
    const loadingInstance = ElLoading.service({ fullscreen: true })
    axios
      .post(import.meta.env.VITE_PUBLIC_BACKEND_URL + '/withdraw', { mailTo: storeSession.loggedUser.email, amount: withDrawalsValue.value })
      .then((response) => {
        if (typeof response.status != 'undefined' && response.status == '201') {
          loadingInstance.close()
          withDrawals.value = false
          Swal.fire({
            icon: 'success',
            title: '¡Genial!',
            text: 'Te hemos enviado un correo con el estado de la solicitud',
          })
        }
      })
      .catch((error) => {
        loadingInstance.close()
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
  } else {
    // Mostrar un mensaje de error o realizar una acción alternativa
    ElMessage.error('Oops! El valor debe ser mayor o igual a $10 USD')
  }
}

function openModalToWithdrawals() {
  withDrawals.value = true
}
</script>

<template>
  <li class="kt-menu__item" aria-haspopup="true">
    <a target="_blank" @click="openModalToWithdrawals()" class="kt-menu__link">
      <i class="kt-menu__link-icon flaticon2-arrow-down"></i>
      <span class="kt-menu__link-text">Retiro</span>
    </a>
  </li>
  <el-dialog title="Retirar dinero" v-model="withDrawals" :center="true">
    <!-- Título del modal -->
    <div class="modal-header">
      <h5 class="modal-title">Recuerda que el valor que vayas a retirar, no puede ser menor a $10 USD</h5>
    </div>
    <!-- Contenido del modal -->
    <div class="modal-body">
      <input type="number" class="form-control" placeholder="Ingrese un valor" v-model="withDrawalsValue" />
    </div>
    <!-- Botones de acción del modal -->
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" @click="withDrawals = false">Cancelar</button>
      <button type="button" class="btn btn-primary" @click="submitWithdrawal">Enviar</button>
    </div>
  </el-dialog>
</template>

<style>
</style>