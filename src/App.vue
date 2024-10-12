<template>
  <div v-if="false" class="app">
    <AppCoreLayoutProvider>
      <RouterView/>
    </AppCoreLayoutProvider>
    <AppGlobalScope />
  </div>
</template>

<script lang="ts">
import { defineComponent, watch, onMounted } from 'vue'
import AppCoreLayoutProvider from '@/core/layout/Provider.vue'
import AppGlobalScope from '@/components/common/GlobalScope.vue'
import { useAuth } from '@/api/auth'
import { $bus, IGlobalBusKeys } from '@/plugins/events/global.bus'

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
    })

    watch(authProvider.user, (user) => {
      $bus.emit(IGlobalBusKeys.auth, user.isAuth)
    })

    onMounted(() => {
      authProvider.mounted()
    })

    const token = authProvider.getJwt()
    if (token && token !== 'logout') {
      window.location.href = `https://new.primetime.su/?auth=${token}`
    } else {
      window.location.href = 'https://new.primetime.su'
    }

    return {
      authProvider,
    }
  },
})
</script>
