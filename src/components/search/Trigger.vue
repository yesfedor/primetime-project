<template>
  <v-menu
    :close-on-content-click="false"
    location="left top"
    content-class="app-search-trigger-menu"
  >
    <template v-slot:activator="{ props }">
      <v-btn
        v-bind="props"
        color="primary"
        variant="text"
        icon="mdi-magnify"
        class="order-0 order-md-1"
      />
    </template>
    <v-list width="320" max-height="500" class="app-search-trigger-menu__list pa-0 mt-n2 me-2 overflow-hidden">
      <v-list-item class="pa-0">
        <v-text-field
          :model-value="searchField"
          :placeholder="$t('search.autocomplete.empty')"
          :loading="isLoading"
          variant="outlined"
          type="search"
          hide-details
          @update:model-value="updateSearch"
          @keyup.enter="openFullPage(searchField)"
        >
          <template #append-inner>
            <v-progress-circular v-if="isLoading" indeterminate size="22" width="2"></v-progress-circular>
          </template>
        </v-text-field>
      </v-list-item>
      <v-list-item v-if="listDataType === 'empty' && searchHistoryHints.length" class="pa-0 pt-3">
        <v-list width="320" class="pa-0">
          <v-list-item
            v-for="hint in searchHistoryHints"
            :key="hint.id"
            link
            class="app-search-trigger-menu-hint-history text-truncate"
          >
            <v-list-item-title @click="openFullPage(hint.query)">{{ hint.query }}</v-list-item-title>
            <template #append>
              <v-btn
                :disabled="loadingRemoveHistoryHint"
                :loading="loadingRemoveHistoryHint"
                variant="text"
                density="comfortable"
                icon="mdi-close"
                class="app-search-trigger-menu-hint-history__append transition-swing"
                @click.stop="removeHistoryHint(hint.id)"
              />
            </template>
          </v-list-item>
        </v-list>
      </v-list-item>
      <v-list-item v-if="listDataType !== 'empty'" class="pa-0 py-2">
        <v-list width="320" class="pa-0">
          <v-list-item v-if="listDataType === 'no_result'" key="no_result" :title="$t('search.autocomplete.no_result')" />
          <v-list-item
            v-for="(hint, index) in hints"
            :key="hint.kinopoiskId"
            :tabindex="index + 1"
            :prepend-avatar="getPosterImageByKinopoiskid(hint.kinopoiskId)"
            :to="getRouteToWatchPage(hint.kinopoiskId)"
            link
            :class="{ 'd-none': !hint.nameRu }"
            class="text-truncate"
          >
            <v-list-item-media></v-list-item-media>
            <v-list-item-title>{{ hint.nameRu }}</v-list-item-title>
            <v-list-item-subtitle class="d-flex align-center">
              <span>{{ $t(`watch.type.${hint.type}`) }}</span>
              <span class="px-2"> - </span>
              <span>{{ hint.year }}</span>
              <template  v-if="hint.ratingKinopoisk !== 'null'">
                <span class="px-2"> - </span>
                <span class="d-flex align-center">
                  <v-icon icon="mdi-star" size="16" class="pe-2" /> {{ hint.ratingKinopoisk }}
                </span>
              </template>
            </v-list-item-subtitle>
          </v-list-item>
        </v-list>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted, watch } from 'vue'
import { watchDebounced } from '@vueuse/core'
import type { WatchApiFastSearchItem, WatchApiFastSearchHistoryItem} from '@/api/watch'
import { watchApi } from '@/api/watch'
import { useAuth } from '@/api/auth'
import router from '@/router'
import { RouteNamesEnum } from '@/router/router.types'
import { getPosterImageByKinopoiskid } from '@/utils/watch'
import { UTM_SOURCE_KEY, UTM_SOURCE } from '@/const/utm'

const authProvider = useAuth()

const searchField = ref('')
const isLoading = ref(false)

const hints = ref<WatchApiFastSearchItem[]>([])

const listDataType = computed(() => {
  if (isLoading.value) {
    return 'loading'
  }
  if (hints.value.length) {
    return 'result'
  }
  if (searchField.value && searchField.value.length > 3) {
    return 'no_result'
  }
  return 'empty'
})

const isQueryValid = (query: string) => {
  return query && query.length > 2
}

const updateSearch = (input: string) => {
  if (isQueryValid(input)) {
    isLoading.value = true
  }
  searchField.value = input
}

watchDebounced(searchField, async (query: string) => {
  if (!isQueryValid(query)) {
    isLoading.value = false
    return (hints.value = [])
  }
  const result = await watchApi.fastSearch(query, authProvider.getJwt(), await authProvider.getClientId())
  if (result?.total) {
    hints.value = result.content.slice(0, 9)
  } else {
    hints.value = []
  }
  isLoading.value = false
}, { debounce: 500, maxWait: 1500 })

const openFullPage = (search: string) => {
  searchField.value = ''
  router.push({
    name: RouteNamesEnum.search,
    params: {
      search: encodeURIComponent(search),
    },
    query: {
      [UTM_SOURCE_KEY]: UTM_SOURCE.searchbox,
    },
  })
}

const searchHistoryHints = ref<WatchApiFastSearchHistoryItem[]>([])

const loadSearchHistoryHints = async () => {
  if (!authProvider.user.isAuth) {
    return false
  }
  const result = await watchApi.fastSearchHistory(authProvider.getJwt(), await authProvider.getClientId())
  if (!result) {
    searchHistoryHints.value = []
    return
  }
  searchHistoryHints.value = result.queries
}

const loadingRemoveHistoryHint = ref(false)
const removeHistoryHint = async (id: string) => {
  loadingRemoveHistoryHint.value = false
  if (!authProvider.getJwt()) {
    return
  }
  loadingRemoveHistoryHint.value = true
  const result = await watchApi.fastSearchHistoryDelete(id, authProvider.getJwt(), await authProvider.getClientId())
  if (!result) {
    return
  }
  await loadSearchHistoryHints()
  loadingRemoveHistoryHint.value = false
}

onMounted(() => {
  loadSearchHistoryHints()
})

watch(authProvider.user, (user) => {
  if (user.isAuth) {
    loadSearchHistoryHints()
  } else {
    searchHistoryHints.value = []
  }
})

const getRouteToWatchPage = (kinopoiskId: number) => {
  return {
    name: RouteNamesEnum.watch,
    params: {
      kpid: kinopoiskId,
    },
    query: {
      [UTM_SOURCE_KEY]: UTM_SOURCE.searchhint,
    },
  }
}
</script>

<style lang="scss">
.app-search-trigger-menu {
  &__list {
    min-width: 320px;
    .v-field__input {
      padding: 4px 12px !important;
      min-height: 48px !important;
      height: 48px !important;
    }
  }
  &-hint-history {
    &__append {
      display: none;
    }
    &:hover &__append {
      display: block;
    }
  }
}
</style>
