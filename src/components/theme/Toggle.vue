<template>
  <v-btn icon @click="toggleTheme">
    <v-icon color="primary">{{ iconName }}</v-icon>
  </v-btn>
</template>

<script lang="ts">
import { computed } from 'vue'
import { useTheme } from 'vuetify'
import { AppThemesEnum } from '@/plugins/vuetify/types'

export default {
  name: 'AppThemeToggle',
  setup () {
    const theme = useTheme()
    const iconName = computed(() => theme.global.current.value.dark ? 'mdi-weather-sunset-up' : 'mdi-weather-sunset-down')
    return {
      iconName,
      toggleTheme: () => theme.global.name.value = theme.global.current.value.dark ? AppThemesEnum.light : AppThemesEnum.dark,
    }
  },
}
</script>
