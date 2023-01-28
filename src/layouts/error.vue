<template>
  <v-app>
    <v-layout :full-height="true" class="layout layout--error">
      <AppHeader />
      <AppNavigationDrawer />
      <v-main>
        <slot />
      </v-main>
      <AppGlobalScope />
    </v-layout>
  </v-app>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import AppHeader from '@/components/common/Header.vue'
import AppNavigationDrawer from '@/components/navigation/Drawer.vue'

export default defineComponent({
  name: 'error',
  components: {
    AppHeader,
    AppNavigationDrawer,
  },
})
</script>
