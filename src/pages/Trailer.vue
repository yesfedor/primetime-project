<template>
  <v-dialog
    v-model:model-value="popupIsOpen"
    scroll-strategy="block"
    fullscreen
    absolute
    content-class="app-trailer-page"
  >
    <v-container fluid class="fill-height bg-black app-trailer-page__wrapper wrapper">
      <v-row v-if="!isShowError && trailerData">
        <v-col offset-md="1" align-self="center" cols="12" md="5" class="wrapper__info">
          <h1 class="text-h6 text-md-4">
            <span class="text-capitalize">{{ trailerType }}</span>
            <span>{{ trailerData.nameRu }}</span>
          </h1>
          <text-clamp :text='trailerData.description' :max-lines='trailerDescriptionLines'>
            <template #before>
              <span class="text-h6">{{ $t('trailer.descripton') }}</span>
            </template>
          </text-clamp>
          <AppTraierInfo :item="trailerData" />
          <div class="text-h6 text-md-4 mt-2 d-flex align-center justify-space-between">
            <span class="mr-2">{{ $t('trailer.actions.register') }}</span>
            <v-btn class="mt-1 mt-md-0" @click="routeToAuthPage">{{ $t('auth.register') }}</v-btn>
          </div>
        </v-col>
        <v-col cols="12" md="6" class="wrapper__image">
          <v-img width="100%" aspect-ratio="16/9" gradient="0deg, rgba(0,0,0,.85) 0%, rgba(0,0,0,.15) 100%"  :src="trailerData.posterUrl" :alt="trailerData.nameRu" />
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
import AppTraierInfo from '@/components/watch/trailer/Info.vue'
import { capitalizeFirstLetter } from '@/utils/capitalizeFirstLetter.helper'
import TextClamp from 'vue3-text-clamp'
import { useDisplay } from 'vuetify/lib/framework.mjs'

const display = useDisplay()
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
const trailerDescriptionLines = computed(() => {
  if (display.mdAndUp.value) {
    return false
  }
  return 3
})
const trailerType = computed(() => {
  if (trailerData.value) {
    return t(`watch.type.${trailerData.value.type}`) + ': '
  }
  return ''
})

const routeToAuthPage = () => {
  router.push({
    name: RouteNamesEnum.auth,
    query: {
      [AUTH_FROM_KEY]: resolvedAuthUrl.href,
      [UTM_SOURCE_KEY]: UTM_SOURCE.trailerpage,
    },
  })
}

const resolvedAuthUrl = router.resolve({
  name: RouteNamesEnum.watch,
  params: {
    kpid: route.params.kpid,
  },
})

watch(popupIsOpen, (popupState) => {
  if (!popupState) {
    routeToAuthPage()
  }
})

onMounted(() => loadTrailerData())
</script>

<style lang="scss">
.app-trailer-page {
  overflow: hidden !important;
  & &__wrapper.wrapper {
    .wrapper__content, .wrapper__image {
      height: 600px;
    }
    .wrapper__image {
      img {
        object-fit: none;
      }
    }
  }
}
</style>
