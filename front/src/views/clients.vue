<script setup>
import { ref, reactive, onBeforeMount } from 'vue'
import Axios from 'axios'
import AppLayout from '../layouts/AppLayout.vue'
import { ElNotification } from 'element-plus'
import Swal from 'sweetalert2'

const axios = Axios.create({
  baseURL: import.meta.env.VITE_PUBLIC_BACKEND_URL,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
})

const tableData = ref([])
const operationsData = ref([])
const selectedClient = ref('')
const selectedClientData = ref([])
const dialogVisible = ref(false)
const dialogVisibleUser = ref(false)
const labelPosition = ref('right')
const formLabelAlign = reactive({
  price_order: '',
  price_actual: '',
  open_date: '',
  status: '',
  direction: '',
  initial_cap: '',
})

async function loadOperations(index, row) {
  await axios
    .get(import.meta.env.VITE_PUBLIC_BACKEND_URL + '/operations/list/' + row._id)
    .then((response) => {
      if (typeof response.status != 'undefined' && response.status == '201') {
        operationsData.value = response.data.content
        selectedClient.value = row._id
        selectedClientData.value = row
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

async function openOperation(index, row) {
  await axios
    .get(import.meta.env.VITE_PUBLIC_BACKEND_URL + '/operations/show/' + row._id)
    .then((response) => {
      if (typeof response.status != 'undefined' && response.status == '201') {
        formLabelAlign.price_order = response.data.content[0].price_order
        formLabelAlign.price_actual = response.data.content[0].price_actual
        formLabelAlign.open_date = response.data.content[0].open_date
        formLabelAlign.status = response.data.content[0].status
        formLabelAlign.direction = response.data.content[0].direction
        formLabelAlign.initial_cap = response.data.content[0].initial_cap
        formLabelAlign._id = response.data.content[0]._id
        dialogVisible.value = true
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

async function updateOperationInfo() {
  await axios
    .put(import.meta.env.VITE_PUBLIC_BACKEND_URL + '/operations/update/', formLabelAlign)
    .then((response) => {
      if (typeof response.status != 'undefined' && response.status == '201') {
        Swal.fire({
          icon: 'success',
          title: '¡Actualizado!',
          text: 'Se ha actualizado la operación del cliente',
        })
        dialogVisible.value = false
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

function cleanClientInfo() {
  selectedClient.value = ''
  selectedClientData.value = []
}

async function listClients() {
  await axios
    .get(import.meta.env.VITE_PUBLIC_BACKEND_URL + '/clients/list/1')
    .then((response) => {
      if (typeof response.status != 'undefined' && response.status == '201') {
        tableData.value = response.data.content.data
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

async function updateUserInfo() {
  await axios
    .put(import.meta.env.VITE_PUBLIC_BACKEND_URL + '/clients/update/', selectedClientData.value)
    .then((response) => {
      if (typeof response.status != 'undefined' && response.status == '201') {
        Swal.fire({
          icon: 'success',
          title: '¡Actualizado!',
          text: 'Se ha actualizado el cliente',
        })
        dialogVisibleUser.value = false
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

onBeforeMount(() => {
  listClients()
})
</script>

<template>
  <AppLayout>
    <div v-if="selectedClient == ''">
      <h1>Clientes:</h1>
      <el-divider />
      <el-table :data="tableData" border stripe style="width: 100%; color: #111111c4">
        <el-table-column prop="firstname" label="Nombre" width="160" />
        <el-table-column prop="lastname" label="Apellido" width="160" />
        <el-table-column prop="email" label="Correo" width="160" />
        <el-table-column prop="phone" label="Telefono" width="140" />
        <el-table-column prop="amount" label="Saldo" width="110" />
        <el-table-column label="Acciones">
          <template #default="scope">
            <el-button type="primary" size="small" @click="loadOperations(scope.$index, scope.row)">Ver</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div v-else>
      <div class="tittle-operations">
        <div class="back">
          <el-button type="info" @click="cleanClientInfo()">Volver</el-button>
        </div>
        <h1>Operaciones:</h1>
      </div>
      <div>
        <el-row>
          <!-- Columna con información del cliente -->
          <el-col :span="12">
            <el-divider />
            <h3>Información del cliente:</h3>
            <h6>
              <b>Nombre:</b>
              {{ selectedClientData.firstname }}
            </h6>
            <h6>
              <b>Apellido:</b>
              {{ selectedClientData.lastname }}
            </h6>
            <h6>
              <b>Correo:</b>
              {{ selectedClientData.email }}
            </h6>
            <h6>
              <b>Telefono:</b>
              {{ selectedClientData.phone }}
            </h6>
            <el-divider />
          </el-col>

          <!-- Columna con botón de edición -->
          <el-col :span="12">
            <el-divider />
            <h3>Editar saldo disponible del cliente:</h3>
            <h6>
              <b>Saldo:</b>
              {{ selectedClientData.amount }}
            </h6>
            <div style="display: flex; align-items: center; justify-content: center; height: 28%">
              <el-button type="primary" @click="dialogVisibleUser = true">Editar</el-button>
            </div>
            <el-divider />
          </el-col>
        </el-row>
      </div>
      <el-table :data="operationsData" stripe style="width: 100%">
        <el-table-column prop="price_order" label="Precio de la orden" width="180" />
        <el-table-column prop="price_actual" label="Precio actual" width="180" />
        <el-table-column prop="open_date" label="Hora de apertura" />
        <el-table-column prop="status" label="Activo" />
        <el-table-column prop="direction" label="Direccion" />
        <el-table-column prop="initial_cap" label="Inversion inicial" />
        <el-table-column label="Acciones">
          <template #default="scope">
            <el-button type="success" size="small" @click="openOperation(scope.$index, scope.row)">Editar</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <el-dialog v-model="dialogVisible" title="Modificar operación" width="40%">
      <el-form :label-position="labelPosition" label-width="30%" :model="formLabelAlign" style="max-width: 460px">
        <el-form-item label="Precio de la orden">
          <el-input v-model="formLabelAlign.price_order" />
        </el-form-item>
        <el-form-item label="Precio actual">
          <el-input v-model="formLabelAlign.price_actual" />
        </el-form-item>
        <el-form-item label="Hora de apertura">
          <el-input v-model="formLabelAlign.open_date" />
        </el-form-item>
        <el-form-item label="Activo">
          <el-input v-model="formLabelAlign.status" />
        </el-form-item>
        <el-form-item label="Direccion">
          <el-input v-model="formLabelAlign.direction" />
        </el-form-item>
        <el-form-item label="Inversion inicial">
          <el-input v-model="formLabelAlign.initial_cap" />
        </el-form-item>
      </el-form>
      <template #footer>
        <span class="dialog-footer">
          <el-button @click="dialogVisible = false">Cancelar</el-button>
          <el-button type="success" @click="updateOperationInfo()">Confirmar</el-button>
        </span>
      </template>
    </el-dialog>
    <el-dialog v-model="dialogVisibleUser" title="Modificar datos del usuario" width="40%">
      <el-form :label-position="labelPosition" label-width="30%" :model="selectedClientData" style="max-width: 460px">
        <el-form-item label="Nombre">
          <el-input v-model="selectedClientData.firstname" />
        </el-form-item>
        <el-form-item label="Apellido">
          <el-input v-model="selectedClientData.lastname" />
        </el-form-item>
        <el-form-item label="Correo">
          <el-input v-model="selectedClientData.email" />
        </el-form-item>
        <el-form-item label="Telefono">
          <el-input v-model="selectedClientData.phone" />
        </el-form-item>
        <el-form-item label="Saldo">
          <el-input v-model="selectedClientData.amount" />
        </el-form-item>
      </el-form>
      <template #footer>
        <span class="dialog-footer">
          <el-button @click="dialogVisibleUser = false">Cancelar</el-button>
          <el-button type="success" @click="updateUserInfo()">Confirmar</el-button>
        </span>
      </template>
    </el-dialog>
  </AppLayout>
</template>
<style scoped>
.back {
  float: right;
}

.dialog-footer button:first-child {
  margin-right: 10px;
}
</style>
