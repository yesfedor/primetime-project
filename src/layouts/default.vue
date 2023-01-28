<template>
  <v-app>
    <v-layout :full-height="true" class="layout layout--default">
      <AppHeader />
      <AppNavigationDrawer />
      <v-main>
        <slot />
      </v-main>
    </v-layout>
  </v-app>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import AppHeader from '@/components/common/Header.vue'
import AppNavigationDrawer from '@/components/navigation/Drawer.vue'
export default defineComponent({
  name: 'default',
  components: {
    AppHeader,
    AppNavigationDrawer,
  },
})
</script>
