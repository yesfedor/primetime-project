<template>
  <v-container fluid class="app-watch">
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
import { watchApi, WatchApiGetRecommendationsDataByKpid } from '@/api/watch'
import { useAuth } from '@/api/auth'
import AppWatchPlayer from '@/components/watch/Player.vue'
import AppWatchInfoTable from '@/components/watch/InfoTable.vue'
import AppWatchList from '@/components/watch/List.vue'

const route = useRoute()
const authProvider = useAuth()

const kpid = computed(() => route.params.kpid + '')

// watchData
const watchData = ref({})
const watchIsLoading = ref(false)
const getWatchDataByKpid = async () => {
  watchIsLoading.value = true
  const result = await watchApi.getDataByKpid(kpid.value, authProvider.getJwt())
  if (result?.id) {
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

const init = () => {
  getWatchDataByKpid()
  getRecommendationsData()
}

watch(kpid, () => {
  init()
})

onMounted(() => {
  init()
})
</script>
