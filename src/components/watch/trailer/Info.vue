<template>
  <div class="d-flex flex-column mt-2 app-trailer-info">
    <div v-if="Number(item.ratingKinopoisk)" class="d-flex align-center justify-start">
      <div
        :aria-label="$t('trailer.info.rating')"
        :class="ratingClass"
        class="d-flex align-center jusitfy-center mr-2 app-trailer-info__rating"
      >
        <v-icon>mdi-star</v-icon>
        <span class="text-h6">{{ item.ratingKinopoisk }}</span>
      </div>
      <span :aria-label="$t('trailer.info.rating.viewersCount')" class="text-h6 app-trailer-info__views">{{ item.ratingKinopoiskVoteCount }}</span>
    </div>
    <div class="d-flex flex-column flex-sm-row align-start align-sm-center justify-start mt-2">
      <div v-if="genresList" class="d-flex">
        <h4 v-for="(genre, index) in genresList" :key="index" class="mr-2">{{ genre }}</h4>
      </div>
      <v-divider vertical class="d-none d-md-block mr-2" />
      <div v-if="countriesList" class="d-flex">
        <h4 v-for="(country, index) in countriesList" :key="index" class="mr-2">{{ country }}</h4>
      </div>
      <div v-if="prettyDuration">
        <v-divider vertical class="d-none d-md-block mr-2" />
        <h4 class="mr-2">{{ prettyDuration }}</h4>
      </div>
      <div v-if="item.ratingAgeLimits">
        <v-divider vertical class="d-none d-md-block mr-2" />
        <h4>{{ ratingLimits }}</h4>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed, defineProps, toRefs } from 'vue'
import type { WatchApiExpandedItem } from '@/api/watch'
import { transferStringToList } from '@/utils/transfer'
import { prettyCinemaDuration } from '@/utils/time'

interface Props {
  item: WatchApiExpandedItem
}

const props = defineProps<Props>()
const { item } = toRefs(props)

const ratingClass = computed(() => {
  const movieRating = Number(item.value.ratingKinopoisk)
  if (movieRating > 7) {
    return '--green'
  }
  if (movieRating > 4) {
    return '--yellow'
  }
  return '--red'
})
const genresList = computed(() => transferStringToList(item.value.genres, ',', 3))
const countriesList = computed(() => transferStringToList(item.value.countries, ',', 3))
const prettyDuration = computed(() => prettyCinemaDuration(item.value.filmLength))
const ratingLimits = computed(() => item.value.ratingAgeLimits + '+')
</script>
