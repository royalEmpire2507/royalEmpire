import { createWebHashHistory, createRouter } from "vue-router";
import Axios from 'axios';
import routes from './routes'
import { ElLoading } from 'element-plus'

const router = createRouter({
  history: createWebHashHistory(),
  routes,
  mode: 'history',
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0, left: 0 }
    }
  },
})

async function loadedStores() {
  const loadingInstance = ElLoading.service({ fullscreen: true })
  loadingInstance.close()
}

const axios = Axios.create({
  baseURL: import.meta.env.VITE_PUBLIC_BACKEND_URL,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
})

router.beforeResolve(async (routeTo, routeFrom, next) => {
  const loadingInstance = ElLoading.service({ fullscreen: true })
  if (routeTo.name !== 'login' && routeTo.name !== 'register') {
    await axios
      .get(import.meta.env.VITE_PUBLIC_BACKEND_URL + '/user/data')
      .then(async (response) => {
        if (typeof response.status != 'undefined' && response.status == '201') {
          await loadedStores(response.data.content)
          next()

          loadingInstance.close()
        } else {
          loadingInstance.close()
          next({ name: 'login' })
        }
      })
      .catch((error) => {
        if (error.response.status == 401 || error.response.status == 500) {
          console.log()
        }
        loadingInstance.close()
        next({ name: 'login' })
      })
  } else {
    loadingInstance.close()
    next()
  }
})

export default router
