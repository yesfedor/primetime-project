<template>
  <v-layout class="layout layout--default">
    <AppHeader />
    <AppNavigationDrawer />
    <v-main>
      <slot />
    </v-main>
    <AppGlobalScope />
  </v-layout>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import AppHeader from '@/components/common/Header.vue'
import AppNavigationDrawer from '@/components/navigation/Drawer.vue'
import AppGlobalScope from '@/components/common/GlobalScope.vue'

export default defineComponent({
  name: 'default',
  components: {
    AppHeader,
    AppNavigationDrawer,
    AppGlobalScope,
  },
})
</script>
