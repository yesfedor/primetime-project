<template>
  <v-app-bar-nav-icon v-if="!$vuetify.display.mobile" @click="toggleWidth" />
</template>
<script lang="ts">
import { defineComponent } from 'vue'
import { useNavigationDrawer } from '@/composables/useNavigationDrawer'

export default defineComponent({
  name: 'AppNavigationToggle',
  setup() {
    const { navigationDrawer, toggleWidth } = useNavigationDrawer()
    return {
      navigationDrawer,
      toggleWidth,
    }
  },
})
</script>
