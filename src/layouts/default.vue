<template>
  <v-app>
    <AppHeader />
    <AppNavigationDrawer />
    <v-main>
      <slot />
    </v-main>
    <AppNavigationBottom />
  </v-app>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import AppHeader from '@/components/common/Header.vue'
import AppNavigationDrawer from '@/components/navigation/Drawer.vue'
import AppNavigationBottom from '@/components/navigation/Bottom.vue'

export default defineComponent({
  name: 'default',
  components: {
    AppHeader,
    AppNavigationDrawer,
    AppNavigationBottom,
  },
})
</script>
