import axios from 'axios'
// import store from '@/state/store'

export default [
  // {
  //   path: '/login',
  //   name: 'login',
  //   component: () => import('../views/account/login.vue'),
  //   meta: {
  //     title: 'Login',
  //     beforeResolve(routeTo, routeFrom, next) {
  //       // If the user is already logged in
  //       // if (store.getters['auth/loggedIn']) {
  //       //   // Redirect to the home page instead
  //       //   next({ name: 'default' })
  //       // } else {
  //       // Continue to the login page
  //       next()
  //       // }
  //     },
  //   },
  // },
  {
    path: '/',
    name: 'default',
    meta: {
      title: 'defaultVue',
      authRequired: true,
    },
    component: () => import('../views/default.vue'),
  },
  {
    path: '/clients',
    name: 'clients',
    meta: {
      title: 'clients',
      authRequired: true,
    },
    component: () => import('../views/clients.vue'),
  },
  {
    path: '/wallets',
    name: 'wallets',
    meta: {
      title: 'wallets',
      authRequired: true,
    },
    component: () => import('../views/wallets.vue'),
  },
  {
    path: '/profile',
    default: 'profile',
    name: 'profile',
    meta: {
      title: 'Profile',
      authRequired: true,
    },
    component: () => import('../views/user/userProfile.vue')
  },
  {
    path: '/login',
    name: 'login',
    default: 'login',
    meta: {
      title: 'login',
      authRequired: false,
    },
    component: () => import('../views/user/login.vue')
  },
  {
    path: '/register',
    default: 'register',
    name: 'register',
    meta: {
      title: 'register',
      authRequired: false,
    },
    component: () => import('../views/user/register.vue')
  }
]
