<template>
  <v-dialog
    v-model:model-value="popupIsOpen"
    fullscreen
    absolute
  >
    <v-container fluid class="fill-height bg-black">
      <v-row v-if="!isShowError && trailerData">
        <v-col cols="12" md="8">
          <h1 class="text-h4">
            <span class="text-capitalize">{{ trailerType }}</span>
            <span>{{ trailerData.nameRu }}</span>
          </h1>
          <p>
            <span class="text-h6">{{ $t('trailer.descripton') }}</span>
            <span>{{ trailerData.description }}</span>
          </p>
          <p>
            <span class="text-h6">{{ trailerData.ratingKinopoisk }}</span>
          </p>
        </v-col>
        <v-col cols="12" md="4">
        </v-col>
      </v-row>
    </v-container>
  </v-dialog>
</template>

<script lang="ts" setup>
import { onMounted, ref, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { UTM_SOURCE_KEY, UTM_SOURCE } from '@/const/utm'
import { useTitle } from '@vueuse/core'
import { watchApi } from '@/api/watch'
import type { WatchApiExpandedItem } from '@/api/watch'
import { useI18n } from 'vue-i18n'
import { AUTH_FROM_KEY } from '@/router/routes'
import { RouteNamesEnum } from '@/router/router.types'
import { capitalizeFirstLetter } from '@/utils/capitalizeFirstLetter.helper'

const router = useRouter()
const route = useRoute()
const { t } = useI18n()
useTitle(t('app.loading'))

const popupIsOpen = ref(true)
const trailerIsLoading = ref(false)
const trailerData = ref<WatchApiExpandedItem | null>(null)

const loadTrailerData = async () => {
  trailerIsLoading.value = true
  const kpid = route.params?.kpid
  if (!kpid || typeof kpid === 'object') {
    trailerIsLoading.value = false
    return
  }
  const result = await watchApi.getTrailerData(kpid)
  if (result?.trailer_src) {
    useTitle(t('watch.share.title', {
      type: capitalizeFirstLetter(t(`watch.type.${result.type}`)),
      title: result.nameRu || result.nameEn,
      year: result.startYear && result.endYear ? `${result.startYear} - ${result.endYear}` : result.year,
    }))
    trailerData.value = result
  }
  trailerIsLoading.value = false
}

const isShowError = computed(() => trailerIsLoading.value || !trailerData.value)
const trailerType = computed(() => {
  if (trailerData.value) {
    return t(`watch.type.${trailerData.value.type}`) + ': '
  }
  return ''
})

const resolvedAuthUrl = router.resolve({
  name: RouteNamesEnum.watch,
  params: {
    kpid: route.params.kpid,
  },
})

watch(popupIsOpen, (popupState) => {
  if (!popupState) {
    router.push({
      name: RouteNamesEnum.auth,
      query: {
        [AUTH_FROM_KEY]: resolvedAuthUrl.href,
        [UTM_SOURCE_KEY]: UTM_SOURCE.trailerpage,
      },
    })
  }
})

onMounted(() => loadTrailerData())
</script>
