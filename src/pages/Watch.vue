<template>
  <v-container :key="resolveRouterParam" fluid class="app-watch">
    <v-row>
      <v-col cols="12" md="12" lg="9">
        <v-row class="app-watch-primary">
          <v-col cols="12" class="app-watch-primary__player">
            <AppWatchPlayer :kinopoisk-id="kpid" :is-loading="watchIsLoading" />
          </v-col>
          <v-col cols="12" class="app-watch-primary__info-table">
            <AppWatchInfoTable :data="watchData" :is-loading="watchIsLoading" />
          </v-col>
        </v-row>
      </v-col>
      <v-col cols="12" md="12" lg="3">
        <v-row class="app-watch-secondary">
          <v-col cols="12" class="app-watch-secondary__recommendations">
            <AppWatchList
              :list="recommendationsData"
              :is-loading="recommendationsDataIsLoading"
              :cards-sizes="recommendationsCardSizes"
              height-parallax="200px"
              use-skeleton
            />
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts" setup>
import { ref, Ref, computed, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useTitle } from '@vueuse/core'
import { useI18n } from 'vue-i18n'
import {watchApi, WatchApiExpandedItem, WatchApiGetRecommendationsDataByKpid} from '@/api/watch'
import { useAuth } from '@/api/auth'
import AppWatchPlayer from '@/components/watch/Player.vue'
import AppWatchInfoTable from '@/components/watch/InfoTable.vue'
import AppWatchList from '@/components/watch/List.vue'
import { capitalizeFirstLetter } from '@/utils/capitalizeFirstLetter.helper'

const i18n = useI18n()
useTitle(i18n.t('app.loading'))

const route = useRoute()
const authProvider = useAuth()

// watchData
const watchData = ref<WatchApiExpandedItem | { [key: string]: string }>({})
const watchIsLoading = ref(false)

const resolveRouterParam = computed(() => route.params.kpid + '')
const kpid = computed(() => {
  if (watchData.value?.kinopoiskId) {
    return watchData.value.kinopoiskId
  }
  return '0'
})

const getWatchDataBySlug = async () => {
  watchIsLoading.value = true
  const result = await watchApi.getDataBySlug(resolveRouterParam.value, authProvider.getJwt())
  if (result?.id) {
    useTitle(i18n.t('watch.share.title', {
      type: capitalizeFirstLetter(i18n.t(`watch.type.${result.type}`)),
      title: result.nameRu || result.nameEn,
      year: Number(result.startYear) && Number(result.endYear) ? `${result.startYear} - ${result.endYear}` : result.year,
    }))
    watchData.value = result
  }
  watchIsLoading.value = false
}

const getWatchDataByKpid = async () => {
  watchIsLoading.value = true
  const result = await watchApi.getDataByKpid(resolveRouterParam.value, authProvider.getJwt())
  if (result?.id) {
  useTitle(i18n.t('watch.share.title', {
    type: capitalizeFirstLetter(i18n.t(`watch.type.${result.type}`)),
    title: result.nameRu || result.nameEn,
    year: Number(result.startYear) && Number(result.endYear) ? `${result.startYear} - ${result.endYear}` : result.year,
  }))
    watchData.value = result
  }
  watchIsLoading.value = false
}

// recommendationsData
const recommendationsData = ref([]) as Ref<WatchApiGetRecommendationsDataByKpid['items']>
const recommendationsDataIsLoading = ref(false)
const recommendationsCardSizes = {
  cols: 12,
  sm: 12,
  md: 12,
  lg: 12,
  xl: 12,
}
const getRecommendationsData = async () => {
  recommendationsDataIsLoading.value = true
  const result = await watchApi.getRecommendationsDataByKpid(kpid.value)
  if (result?.total) {
    recommendationsData.value = result.items
  }
  recommendationsDataIsLoading.value = false
}

function reset() {
  watchData.value = {}
  recommendationsData.value = []
}

const init = async () => {
  if (resolveRouterParam.value.includes('-')) {
    await getWatchDataBySlug()
  } else {
    await getWatchDataByKpid()
  }
  await getRecommendationsData()
}

watch(resolveRouterParam, async () => {
  reset()
  await init()
})

onMounted(async () => {
  await init()
})
</script>
