<template>
  <v-app-bar>
    <template #append>
      <AppThemeToggle />
    </template>
  </v-app-bar>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import AppThemeToggle from '@/components/theme/Toggle.vue'

export default defineComponent({
  name: 'AppHeader',
  components: {
    AppThemeToggle,
  },
})
</script>
