<template>
  <div class="d-flex flex-column mt-2 app-trailer-info">
    <div class="d-flex align-center justify-start">
      <div class="d-flex align-center jusitfy-center mr-2 app-trailer-info__rating" :aria-label="$t('Рейтинг')" :class="ratingClass">
        <v-icon>mdi-star</v-icon>
        <span class="text-h6">{{ item.ratingKinopoisk }}</span>
      </div>
      <span :aria-label="$t('Колличество отзывов')" class="text-h6 app-trailer-info__views">{{ item.ratingKinopoiskVoteCount }}</span>
    </div>
    <div v-if="genresList || countriesList" class="d-flex align-center justify-start mt-2">
      <h4 v-for="(genre, index) in genresList" :key="index" class="mr-2">{{ genre }}</h4>
      <v-divider vertical class="mx-2" />
      <h4 v-for="(country, index) in countriesList" :key="index" class="mr-2">{{ country }}</h4>
      <v-divider vertical class="mx-2" />
      <h4>{{ prettyDuration }}</h4>
      <v-divider vertical class="mx-2" />
      <h4>{{ ratingLimits }}</h4>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed, defineProps, toRefs } from 'vue'
import type { WatchApiExpandedItem } from '@/api/watch'
import { useI18n } from 'vue-i18n'
import { transferStringToList } from '@/utils/transfer'
import { prettyCinemaDuration } from '@/utils/time'

interface Props {
  item: WatchApiExpandedItem
}

const { t } = useI18n()
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
