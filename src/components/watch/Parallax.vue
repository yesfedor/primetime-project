<template>
  <v-parallax :src="posterUrl || defaultPosterUrl" :style="parallaxStyles" class="app-watch-parallax">
    <div
      :class="{ 'align-center justify-center': loading, 'align-end justify-start': !loading }"
      class="app-watch-parallax__content d-flex fill-height"
    >
      <v-col v-if="!loading">
        <span :class="customClass" class="d-block text-h4 font-weight-bold text-white pa-3 pa-lg-5">
          {{ $t(labelKey) }}
        </span>
      </v-col>
      <v-progress-circular v-else color="white" indeterminate size="32" width="4" />
    </div>
  </v-parallax>
</template>

<script lang="ts" setup>
// @ts-expect-error typescript error
import { defineProps, toRefs } from 'vue'
import { getPosterImageByKinopoiskid } from '@/utils/watch'

interface Props {
  labelKey: string
  posterUrl: string
  customClass?: string
  loading?: boolean
}

const props = defineProps<Props>()
const { labelKey, posterUrl, customClass, loading } = toRefs(props)

const parallaxStyles = {
  height: '260px',
}

const defaultPosterUrl = getPosterImageByKinopoiskid(571335)
</script>

<style lang="scss">
.app-watch-parallax {
  &__content {
    backdrop-filter: blur(16px);
  }
}
</style>
