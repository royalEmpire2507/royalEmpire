<script setup>
import Axios from 'axios'
import { ElNotification } from 'element-plus'
import { useRouter } from 'vue-router'
import { usersInformationSession } from '../store/usersStore.js'
import Swal from 'sweetalert2'

const storeSession = usersInformationSession()
const { push } = useRouter()

const axios = Axios.create({
  baseURL: import.meta.env.VITE_PUBLIC_BACKEND_URL,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
})

async function sendLogOut() {
  await axios
    .post(import.meta.env.VITE_PUBLIC_BACKEND_URL + '/logout')
    .then((response) => {
      if (typeof response.status != 'undefined' && response.status == '204') {
        push({ path: '/login' })
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
  <div class="kt-header__topbar-item kt-header__topbar-item--user">
    <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
      <div class="kt-header__topbar-user">
        <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
        <span class="kt-header__topbar-username kt-hidden-mobile">{{ storeSession.loggedUser.firstname }}</span>

        <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
        <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">{{ storeSession.userLoggedCorrect == true ? storeSession.loggedUser.firstname.substr(0, 1) : 'U' }}</span>
      </div>
    </div>
    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
      <!--begin: Head -->
      <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-color: #111111de">
        <div class="kt-user-card__avatar">
          <span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success">S</span>
        </div>
        <div class="kt-user-card__name">Royal Empire</div>
      </div>

      <!--end: Head -->

      <!--begin: Navigation -->
      <div class="kt-notification">
        <a href="http://localhost:3000/#/profile" class="kt-notification__item">
          <div class="kt-notification__item-icon">
            <i class="flaticon2-calendar-3 kt-font-success"></i>
          </div>
          <div class="kt-notification__item-details">
            <div class="kt-notification__item-title kt-font-bold">Mi Perfil</div>
            <div class="kt-notification__item-time">Configuración del perfil</div>
          </div>
        </a>
        <div class="kt-notification__custom kt-space-between">
          <button @click="sendLogOut" class="btn btn-label btn-label-brand btn-sm btn-bold">Sign Out</button>
        </div>
      </div>

      <!--end: Navigation -->
    </div>
  </div>
</template>
