<template>
  <div class="app-history">
    <v-parallax v-if="firstHistoryItem" :src="firstHistoryItem.posterUrl" :style="parallaxStyles">
      <div class="app-history__parallax-content d-flex align-end justify-start fill-height">
        <v-col>
          <span class="d-block text-h4 font-weight-bold pa-3 pa-lg-5">{{ $t('history.title') }}</span>
        </v-col>
      </div>
    </v-parallax>
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

const parallaxStyles = {
  height: '260px',
}

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

<style lang="scss">
.app-history {
  min-height: 500vh;
  &__parallax-content {
    backdrop-filter: blur(16px);
  }
}
</style>
