<template>
  <v-card link :to="toStaffPage">
    <v-img
      :src="item.posterUrl"
      :height="240"
      scale="0.9"
      gradient="0deg, rgba(0,0,0,.85) 0%, rgba(0,0,0,.15) 100%"
      cover
      class="align-end"
    >
      <v-card-subtitle class="text-caption">
        {{ staffTypeLabel }}
      </v-card-subtitle>
      <v-card-title class="d-flex align-center text-white text-body-2 text-truncate pt-0">
        {{ staffName }}
      </v-card-title>
    </v-img>
  </v-card>
</template>

<script lang="ts" setup>
import { computed, toRefs } from 'vue'
import { type WatchApiGetStaffItem, WatchApiGetStaffType } from '~/api/watch'
import { RouteNamesEnum } from '@/router/router.types'
import { UTM_SOURCE, UTM_SOURCE_KEY } from '@/const/utm'

interface Props {
  item: WatchApiGetStaffItem | null
  staffType: WatchApiGetStaffType
}

const props = defineProps<Props>()
const { item, staffType } = toRefs(props)

const staffTypeLabel = computed(() => {
  switch (staffType.value) {
    case 'WRITER':
      return 'Сценарист'
    case 'PRODUCER':
      return 'Режиссер'
    case 'DESIGN':
      return 'Персонал'
    case 'ACTOR':
    default:
      return 'Актер'
  }
})

const staffName = computed(() => {
  return item.value?.nameRu || item.value?.nameEn || ''
})

const toStaffPage = computed(() => {
  return {
    name: RouteNamesEnum.staff,
    params: {
      staff: item.value?.staffId || 0,
    },
    query: {
      [UTM_SOURCE_KEY]: UTM_SOURCE.watchbox,
    },
  }
})
</script>
