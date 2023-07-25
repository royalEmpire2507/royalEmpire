<script setup>
import { ref } from 'vue'
import Axios from 'axios'
import { ElNotification } from 'element-plus'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
const { t } = useI18n()

// import sha256 from 'crypto-js/sha256';
const { push } = useRouter()
const user = ref('')
const pw = ref('')

const axios = Axios.create({
  baseURL: import.meta.env.VITE_PUBLIC_BACKEND_URL,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
})

async function sendLogIn() {
  var oprtn = user.value.split('@')[1]

  const csrf = () => axios.get(import.meta.env.VITE_PUBLIC_BACKEND_URL + '/sanctum/csrf-cookie')
  await csrf()

  const dataSend = ref({ email: user.value, password: pw.value, remember: false, operation: oprtn })

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
          title: t('notify.Error'),
          message: error.response.data.errors.email[0],
          type: 'error',
        })
      }
    })
}
</script>
<template>
  <div class="container-loggin">
    <div class="left-div">
      <img class="img_logo" src="https://www.crmvox.com/wp-content/uploads/logo.svg" />
      <img class="img_lgn" src="https://crm.wolkvox.com/assets/media/wolkvox_crm_image.png" alt="" />
    </div>
    <div class="rigth-div">
      <div class="tittle-login">
        <h1 style="color: black !important; font-family: Montserrat !important; font-size: 35px; font-weight: 400; margin-top: 25%" v-cloak>Bienvenido a</h1>
        <h1 style="color: black !important; margin-top: -5px !important; font-family: Montserrat !important; font-size: 35px; font-weight: 400" v-cloak>
          Wolkvox
          <b>CRM</b>
        </h1>
      </div>
      <el-form label-width="120px" class="demo-ruleForm">
        <h5 class="mb-0 text-muted" style="text-align: center; color: black !important"><b>Account Sign In</b></h5>
        <el-form-item label="User" style="width: 80%; font-family: Montserrat !important; color: black !important">
          <el-input v-model="user" type="text" autocomplete="off" />
        </el-form-item>
        <div class="form-group">
          <el-form-item label="Password" style="width: 80%; font-family: Montserrat !important; color: black !important">
            <el-input v-model="pw" type="password" autocomplete="off" />
          </el-form-item>
        </div>
        <div class="form-group">
          <el-form-item style="width: 80%">
            <button id="kt_login_signin_submit" class="btn btn-primary btn-elevate kt-login__btn-primary" @click="sendLogIn()">Ingresar</button>
          </el-form-item>
        </div>
      </el-form>
    </div>
  </div>
</template>
<style>
#kt_login_signin_submit {
  font-family: Montserrat !important;
  font-weight: 400;
  background-color: #5867dd;
  border-color: #a2a5b9;
  height: 32px;
  width: 100%;
  padding: 0;
}
.tittle-login {
  text-align: center;
  margin: 2%;
}
.img_lgn {
  width: 86%;
  max-width: 100%;
  height: auto;
  margin: -10% 10% 0% 10%;
}

.img_logo {
  width: 10%;
  height: auto;
  margin: 1%;
  position: absolute;
}

.container-loggin {
  display: flex;
  width: 100%;
  height: 100%;
}

.left-div {
  width: 60%;
  height: 100%;
  position: relative;
}

.rigth-div {
  padding: 3%;
  width: 40%;
  height: 90%;
  position: absolute;
  top: 10px;
  right: 10px;
}

@media (max-width: 750px) {
  .container-loggin {
    display: block;
  }

  .rigth-div {
    top: 25%;
    width: 100%;
  }
}

@media (max-width: 600px) {
  .img_lgn {
    width: 100%;
  }
}
</style>
