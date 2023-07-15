<template>
  <div class="app-history">
    <AppWatchParallax
      :poster-url="cardFirstItem && cardFirstItem.posterUrl || ''"
      :loading="isLoading"
      label-key="history.title"
    />
    <v-container fluid>
      <AppWatchList :list="cardList" :is-loading="isLoading" />
    </v-container>
  </div>
</template>

<script lang="ts" setup>
import { useI18n } from 'vue-i18n'
import { useTitle } from '@vueuse/core'
import { useAuth } from '@/api/auth'
import { watchApi } from '@/api/watch'
import type { WatchApiContentItem } from '@/api/watch'
import { useWatchList } from '@/composables/useWatchList'
import AppWatchList from '@/components/watch/List.vue'
import AppWatchParallax from '@/components/watch/Parallax.vue'

const { t } = useI18n()
useTitle(t('history.title'))

const authProvider = useAuth()

const { cardFirstItem, cardList, isLoading } = useWatchList<WatchApiContentItem>({
  async loadFn() {
    const result = await watchApi.getUserHistory(authProvider.getJwt(), await authProvider.getClientId())
    if (result?.total) {
      return result.content
    }
    return []
  },
})
</script>
