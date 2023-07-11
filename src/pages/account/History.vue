<template>
  <div class="app-history">
    <AppWatchParallax
      :poster-url="firstHistoryItem && firstHistoryItem.posterUrl || ''"
      :loading="loadingUserHistory"
      label-key="history.title"
    />
    <v-container>
      <AppWatchList v-if="userHistory.length" :list="userHistory" />
      <v-row v-else>
        <v-col class="text-center">
          <h2>{{ loadingUserHistory ? $t('app.loading') : $t('app.no_result') }}</h2>
          <v-progress-circular v-if="loadingUserHistory" indeterminate size="32" width="4" class="mt-5" />
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

const loadingUserHistory = ref(false)
const userHistory = ref<WatchApiContentItem[]>([])

const loadUserHistory = async () => {
  loadingUserHistory.value = true
  const result = await watchApi.getUserHistory(authProvider.getJwt(), await authProvider.getClientId())
  if (result?.total) {
    userHistory.value = result.content
  }
  loadingUserHistory.value = false
}

const firstHistoryItem = computed(() => {
  return userHistory.value.length ? userHistory.value[0] : false
})

onMounted(() => {
  loadUserHistory()
})
</script>
