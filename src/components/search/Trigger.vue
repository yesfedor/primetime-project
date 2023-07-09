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
    <v-list class="app-search-trigger-menu__list pa-0 mt-n2 me-2">
      <v-list-item class="pa-0">
        <v-autocomplete
          :search="searchField"
          :items="hintsAutocomplete"
          :no-data-text="noDataText"
          :placeholder="$t('search.autocomplete.empty')"
          variant="outlined"
          item-title="title"
          item-value="kinopoiskId"
          item-children
          hide-details
          type="search"
          clearable
          @update:search="updateSearch"
        >
        <template #item="{ item }">
          <v-list-item :title="item.title" />
        </template>
        </v-autocomplete>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue'
import { watchDebounced } from '@vueuse/core'
import type { WatchApiFastSearchItem } from '@/api/watch'
import { watchApi } from '@/api/watch'
import { useAuth } from '@/api/auth'
import { useI18n } from 'vue-i18n'

const i18n = useI18n()
const authProvider = useAuth()

const searchField = ref('')
const isLoading = ref(false)
const noDataText = computed(() => {
  if (isLoading.value) {
    return i18n.t('search.autocomplete.loading')
  }
  if (searchField.value.length > 3) {
    return i18n.t('search.autocomplete.no_result')
  }
  return i18n.t('search.autocomplete.empty')
})

const hints = ref<WatchApiFastSearchItem[]>([])
const hintsAutocomplete = computed(() => {
  const items: { title: string, kinopoiskId: number }[] = []
  hints.value.forEach((hint) => {
    const item = {
      title: `${hint.nameRu} - (${hint.year})`,
      kinopoiskId: hint.kinopoiskId,
    }
    items.push(item)
  })
  return items.slice(0, 5)
})

const updateSearch = (input: string) => {
  isLoading.value = true
  searchField.value = input
}

watchDebounced(searchField, async (query: string) => {
  if (!query || query.length < 2) {
    return
  }
  const result = await watchApi.fastSearch(query, authProvider.getJwt(), await authProvider.getClientId())
  if (result?.total) {
    console.log(query, result)
    hints.value = result.content
  }
  isLoading.value = false
}, { debounce: 1000, maxWait: 1000 })
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
    .v-field__append-inner {
      display: none;
    }
  }
}
</style>
