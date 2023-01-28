<template>
  <div class="app">
    <AppCoreLayoutProvider>
      <RouterView/>
    </AppCoreLayoutProvider>
    <AppGlobalScope />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import AppCoreLayoutProvider from '@/core/layout/Provider.vue'
import AppGlobalScope from '@/components/common/GlobalScope.vue'

export default defineComponent({
  name: 'App',
  components: {
    AppCoreLayoutProvider,
    AppGlobalScope,
  },
})
</script>
