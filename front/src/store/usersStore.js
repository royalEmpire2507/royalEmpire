import { defineStore } from 'pinia'
import Axios from 'axios'
// import { ElLoading } from 'element-plus'

// import { useRouter } from 'vue-router'

const axios = Axios.create({
  baseURL: import.meta.env.VITE_PUBLIC_BACKEND_URL,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
})

export const usersInformationSession = defineStore('userSession', {
  state: () => ({ loggedUser: [], userLoggedCorrect: false }),

  actions: {
    async userLogged() {
      if (this.userLoggedCorrect == false) {
        // const loadingInstance = ElLoading.service({ fullscreen: true })
        await axios
          .get(`${import.meta.env.VITE_PUBLIC_BACKEND_URL}/user/data`)
          .then((response) => {
            if (response.data.result == 'ok') {
              this.loggedUser = response.data.content
              this.userLoggedCorrect = true
              // loadingInstance.close()
            } else {
              this.loggedUser = []
              // loadingInstance.close()
            }
          })
          .catch((error) => {
            // loadingInstance.close()
            if (error.response.status !== 422) throw error
            console.log(error.response.data.errors)
          })
        // loadingInstance.close()
      }
    },
  },
})