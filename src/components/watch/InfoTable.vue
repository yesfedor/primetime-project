<template>
	<v-row class="app-watch-info-table py-0">
		<v-col cols="9" class="d-flex align-center py-0">
			<v-skeleton-loader :loading="isLoading" width="100%" type="list-item" class="bg-background">
				<h1 class="text-h6">
					<span class="text-truncate text-capitalize">{{ $t(`watch.type.${data.type}`) }}&#160;</span>
					<span>{{ data.nameRu || data.nameEn }}</span>
				</h1>
			</v-skeleton-loader>
		</v-col>
		<v-col cols="3" class="d-flex align-center justify-end py-0">
			<span v-if="data.ratingAgeLimits" class="text-h6">{{ `${data.ratingAgeLimits}+` }}</span>
		</v-col>
		<v-col cols="12" class="pb-0">
			<v-divider />
			<v-card width="100%" class="pt-1 bg-background">
				<v-card-actions class="d-flex align-center justify-center justify-lg-end pa-0">
					<AppWatchSelectPlayer />
					<AppWatchSubscribeManager :kinopoisk-id="kinopoiskId" show-text />
				</v-card-actions>
			</v-card>
		</v-col>
		<v-col cols="12" class="pt-1">
			<v-skeleton-loader :loading="isLoading" type="article" class="py-0">
        <v-table class="w-100">
          <tbody>
            <tr
              v-for="item in tableData"
              :key="item.key"
            >
              <td class="text-body-2">{{ item.label }}</td>
              <td class="text-body-2">{{ item.value }}</td>
            </tr>
          </tbody>
        </v-table>
        <v-divider />
				<v-card>
					<v-card-title class="text-subtitle-1">{{ $t('watch_info.description') }}</v-card-title>
					<v-card-text class="text-body-2">{{ data.description }}</v-card-text>
          <v-divider />
          <v-col cols="12">
            <AppWatchStaffList v-if="!isLoading" :staff="staff" :limited="false" />
          </v-col>
          <v-col v-if="facts?.length" cols="12" class="mb-3">
            <v-row>
              <v-col cols="12" class="pb-0">
                <h3 class="text-subtitle-1">Факты</h3>
              </v-col>
              <v-col v-for="(content, index) in facts" :key="content.id" cols="12" class="py-1">
                <span class="text-caption">#{{ index + 1 }}</span>
                <p class="text-body-2 my-0" v-html="content.text" />
              </v-col>
            </v-row>
          </v-col>
				</v-card>
			</v-skeleton-loader>
		</v-col>
	</v-row>
</template>

<script lang="ts" setup>
import { Ref, toRefs, computed } from 'vue'
import { WatchApiExpandedItem, WatchApiGetFacts, WatchApiGetStaffByKpid } from '@/api/watch'
import AppWatchSubscribeManager from '@/components/watch/SubscribeManager.vue'
import AppWatchSelectPlayer from '@/components/watch/SelectPlayer.vue'
import AppWatchStaffList from '@/components/staff/List.vue'

interface Props {
	data: WatchApiExpandedItem
  staff: WatchApiGetStaffByKpid['staff']
  facts: WatchApiGetFacts['content']
	isLoading?: boolean
}

const props = defineProps<Props>()
const {
  data,
  staff,
  facts,
} = toRefs(props)

const kinopoiskId = computed(() => data.value?.kinopoiskId || '')

const tableData = computed(() => {
  const table: { key: string, label: string, value: string }[] = []
  const keys = [
    'slogan', 'year', 'ratingKinopoisk',
    'filmLength', 'countries', 'genres',
  ]
  const labelByKey: { [key: string]: string } = {
    slogan: 'Слоган',
    year: 'Год',
    countries: 'Страна',
    filmLength: 'Продолжительность',
    genres: 'Жанр',
    ratingKinopoisk: 'Рейтинг',
  }
  keys.forEach((key: string) => {
    let value = data.value[key]
    if (value) {
      switch (key) {
        case 'countries':
          value = value.split(',')[0]
          break
        case 'genres':
          value = value.split(',')
          value = value.join(', ')
          break
        case 'filmLength':
          value += ' мин.'
          break
        case 'year':
          if (data.value?.startYear && data.value?.endYear && data.value?.startYear !== '0') {
            value = `с ${data.value.startYear} по ${data.value.endYear !== '0' ? data.value.endYear : 'н. в.'}`
          }
          break
      }

      if (value) {
        table.push({
          key,
          label: labelByKey[key],
          value,
        })
      }
    }
  })
  return table
})
</script>
