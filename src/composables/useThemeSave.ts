import { onMounted, watch } from 'vue'
import { useTheme } from 'vuetify'
import { AppThemesEnum } from '@/plugins/vuetify/types'

export function useThemeSave() {
  const storageKey = 'Theme'
  const theme = useTheme()
  let savedTheme = localStorage.getItem(storageKey) || AppThemesEnum.dark

  watch(theme.name, (themeName) => {
    savedTheme = themeName
    localStorage.setItem(storageKey, themeName)
  })

  onMounted(() => {
    if (theme.name.value !== savedTheme) {
      theme.global.name.value = savedTheme
    }
  })
}
