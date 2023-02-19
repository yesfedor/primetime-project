<template>
  <div class="app">
    <AppCoreLayoutProvider>
      <RouterView/>
    </AppCoreLayoutProvider>
    <AppGlobalScope />
  </div>
</template>

<script lang="ts">
import { defineComponent, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import AppCoreLayoutProvider from '@/core/layout/Provider.vue'
import AppGlobalScope from '@/components/common/GlobalScope.vue'
import { useAuth, EApiRouterPushName } from '@/api/auth'
import { RouteNamesEnum } from '@/router/router.types'
import { $bus, IGlobalBusKeys } from '@/plugins/events/global.bus'

export default defineComponent({
  name: 'App',
  components: {
    AppCoreLayoutProvider,
    AppGlobalScope,
  },
  setup() {
    const router = useRouter()
    const authProvider = useAuth()
    authProvider.install({
      appId: 1,
      routerPush(routeName) {
        if (routeName === EApiRouterPushName.error) {
          router.push({ name: RouteNamesEnum.auth })
        }
        if (routeName === EApiRouterPushName.main) {
          router.push({ name: RouteNamesEnum.home })
        }
      },
    })

    watch(authProvider.user, (user) => {
      $bus.emit(IGlobalBusKeys.auth, user.isAuth)
    })

    onMounted(() => {
      authProvider.mounted()
    })
  },
})
</script>
