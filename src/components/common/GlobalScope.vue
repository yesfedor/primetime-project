<template>
  <div class="app-global-scope">
    <AppLoaderDialog />
  </div>
</template>

<script lang="ts" setup>
import { watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useThemeSave } from '@/composables/useThemeSave'
import AppLoaderDialog from '@/components/common/LoaderDialog.vue'
import { useAuth } from '@/api/auth'
import { getFailRoute } from '@/router/routes'

useThemeSave()

const router = useRouter()
const route = useRoute()
const authProvider = useAuth()

watch(authProvider.user, (user) => {
  if (route && route.meta?.isNeedAuth && !user.isAuth) {
    router.push(getFailRoute(route))
  }
})
</script>
