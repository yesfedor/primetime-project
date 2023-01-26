<template>
  <AppCoreLayoutProvider>
    <RouterView/>
  </AppCoreLayoutProvider>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import AppCoreLayoutProvider from '@/core/layout/Provider.vue'

export default defineComponent({
  name: 'App',
  components: {
    AppCoreLayoutProvider,
  },
})
</script>
