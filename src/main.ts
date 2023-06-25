import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from '@/App.vue'
import router from '@/router'
import vuetify from '@/plugins/vuetify/create'
import { i18n } from '@/plugins/i18n/create'
import { loadFonts } from '@/plugins/webfontloader'
import Toast, { POSITION } from 'vue-toastification'

loadFonts()

createApp(App)
  .use(createPinia)
  .use(router)
  .use(i18n)
  .use(vuetify)
  .use(Toast, {
    shareAppContext: true,
    position: POSITION.TOP_RIGHT,
    timeout: 4000,
    maxToasts: 8,
    transition: 'Vue-Toastification__fade',
  })
  .mount('#app')
