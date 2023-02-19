<template>
  <div class="app">
    <AppCoreLayoutProvider>
      <RouterView/>
    </AppCoreLayoutProvider>
    <AppGlobalScope />
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted } from 'vue'
import { useAuth } from '@/api/auth'
import AppCoreLayoutProvider from '@/core/layout/Provider.vue'
import AppGlobalScope from '@/components/common/GlobalScope.vue'

export default defineComponent({
  name: 'App',
  components: {
    AppCoreLayoutProvider,
    AppGlobalScope,
  },
  setup() {
    const authProvider = useAuth()
    authProvider.install({
      appId: 1,
      routerPush(routeName, routeReason) {
        console.log(routeName, routeReason)
      },
      storeCommit(what, payload) {
        console.log(what, payload)
      },
    })
    onMounted(() => {
      authProvider.mounted()
    })
  },
})
</script>
