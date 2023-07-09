// Styles
import '@mdi/font/css/materialdesignicons.css'

// Vuetify
import { createVuetify } from 'vuetify'
import { md3 } from 'vuetify/blueprints'
import { aliases, mdi } from 'vuetify/iconsets/mdi'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
// Themes
import { AppThemesEnum } from '@/plugins/vuetify/types'
import { lightTheme } from '@/plugins/vuetify/theme/light'
import { darkTheme } from '@/plugins/vuetify/theme/dark'

export default createVuetify({
  // https://vuetifyjs.com/en/introduction/why-vuetify/#feature-guides
  ssr: false,
  blueprint: md3,
  display: {
    mobileBreakpoint: 'sm',
  },
  defaults: {
    global: {
      elevation: 0,
      flat: true,
    },
  },
  theme: {
    defaultTheme: AppThemesEnum.dark,
    themes: {
      lightTheme,
      darkTheme,
    },
  },
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: {
      mdi,
    },
  },
  components,
  directives,
})
