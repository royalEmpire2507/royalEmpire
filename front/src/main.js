// import './style.css'
import CKEditor from '@ckeditor/ckeditor5-vue'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import App from './App.vue'
import router from './router'
import i18n from './plugins/i18n.js'
import '@fontsource/montserrat'
import moment from 'moment';

const pinia = createPinia()

createApp(App).use(router).use(CKEditor).use(pinia).use(ElementPlus).use(i18n).use(moment).mount('#app')
