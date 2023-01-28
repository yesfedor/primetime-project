<template>
  <v-btn @click="toggleWidth">{{ navigationDrawer.width }}</v-btn>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useNavigationDrawer } from '@/composables/useNavigationDrawer'

export default defineComponent({
  name: 'AppNavigationDrawerToggleWidth',
  setup() {
    const { navigationDrawer, toggleWidth } = useNavigationDrawer()
    return {
      navigationDrawer,
      toggleWidth,
    }
  },
})
</script>
