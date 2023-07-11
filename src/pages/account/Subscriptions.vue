<template>
  <div class="app-subscriptions">
    <AppWatchParallax
      :poster-url="firstSubscriptionsItem && firstSubscriptionsItem.posterUrl || ''"
      :loading="loadingUserSubscriptions"
      label-key="subscriptions.title"
    />
    <v-container>
      <AppWatchList v-if="userSubscriptions.length" :list="userSubscriptions" />
      <v-row v-else>
        <v-col class="text-center">
          <h2>{{ loadingUserSubscriptions ? $t('app.loading') : $t('app.no_result') }}</h2>
          <v-progress-circular v-if="loadingUserSubscriptions" indeterminate size="32" width="4" class="mt-5" />
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted, computed } from 'vue'
import type { WatchApiContentItem } from '@/api/watch'
import { watchApi } from '@/api/watch'
import { useAuth } from '@/api/auth'
import AppWatchList from '@/components/watch/List.vue'
import AppWatchParallax from '@/components/watch/Parallax.vue'

const authProvider = useAuth()

const loadingUserSubscriptions = ref(false)
const userSubscriptions = ref<WatchApiContentItem[]>([])

const loadUserSubscriptions = async () => {
  loadingUserSubscriptions.value = true
  const result = await watchApi.getSubscriptions(authProvider.getJwt(), await authProvider.getClientId())
  if (result?.total) {
    userSubscriptions.value = result.content
  }
  loadingUserSubscriptions.value = false
}

const firstSubscriptionsItem = computed(() => {
  return userSubscriptions.value.length ? userSubscriptions.value[0] : false
})

onMounted(() => {
  loadUserSubscriptions()
})
</script>
