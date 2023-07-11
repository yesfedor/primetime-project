<template>
  <div class="app-search">
    <AppWatchParallax
      :poster-url="firstItem && firstItem.posterUrl || ''"
      :loading="loadingSearch"
      label-key="search.title"
    />
    <v-container>
      <v-col cols="12" md="6" lg="3" class="ps-0">
        <v-text-field
          v-model:model-value="searchField"
          type="search"
          append-inner-icon="mdi-magnify"
          clearable
          @click:append-inner="changedSearchField"
          @keyup.enter="changedSearchField"
        />
      </v-col>
      <AppWatchList v-if="search.length" :list="search" />
      <v-row v-else>
        <v-col class="text-center">
          <h2>{{ loadingSearch ? $t('app.loading') : $t('app.no_result') }}</h2>
          <v-progress-circular v-if="loadingSearch" indeterminate size="32" width="4" class="mt-5" />
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import type { WatchApiContentItem } from '@/api/watch'
import { watchApi } from '@/api/watch'
import { useAuth } from '@/api/auth'
import AppWatchList from '@/components/watch/List.vue'
import AppWatchParallax from '@/components/watch/Parallax.vue'
import { RouteNamesEnum } from '@/router/router.types'
import { UTM_SOURCE_KEY, UTM_SOURCE } from '@/const/utm'

const authProvider = useAuth()

const loadingSearch = ref(false)
const search = ref<WatchApiContentItem[]>([])
const searchField = ref('')
const lastSearchPhrase = ref('')

const loadSearch = async () => {
  loadingSearch.value = true
  lastSearchPhrase.value = searchField.value
  const result = await watchApi.fastSearch(searchField.value, authProvider.getJwt(), await authProvider.getClientId())
  if (result?.total) {
    search.value = result.content as WatchApiContentItem[]
  }
  loadingSearch.value = false
}

const firstItem = computed(() => {
  return search.value.length ? search.value[0] : false
})

const router = useRouter()
const changedSearchField = () => {
  if (loadingSearch.value || lastSearchPhrase.value === searchField.value || !searchField.value) {
    return
  }
  router.push({
    name: RouteNamesEnum.search,
    params: {
      search: encodeURIComponent(searchField.value),
    },
    query: {
      [UTM_SOURCE_KEY]: UTM_SOURCE.searchbox,
    },
  })
  loadSearch()
}

const route = useRoute()
if (typeof route.params.search === 'string') {
  const searchText = decodeURIComponent(route.params.search)
  searchField.value = searchText
  loadSearch()
}
</script>
