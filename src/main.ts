import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from '@/App.vue'
import router from '@/router'
import vuetify from '@/plugins/vuetify/create'
import { i18n } from '@/plugins/i18n/create'
import { loadFonts } from '@/plugins/webfontloader'

loadFonts()

createApp(App)
  .use(createPinia)
  .use(router)
  .use(i18n)
  .use(vuetify)
  .mount('#app')
