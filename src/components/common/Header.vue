<template>
  <v-app-bar :height="56">
    <AppNavigationToggle />
    <v-app-bar-title>
      <AppLogo />
    </v-app-bar-title>
    <template #append>
      <AppThemeToggle />
    </template>
  </v-app-bar>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import AppNavigationToggle from '@/components/navigation/Toggle.vue'
import AppLogo from '@/components/common/Logo.vue'
import AppThemeToggle from '@/components/theme/Toggle.vue'

export default defineComponent({
  name: 'AppHeader',
  components: {
    AppNavigationToggle,
    AppLogo,
    AppThemeToggle,
  },
})
</script>
