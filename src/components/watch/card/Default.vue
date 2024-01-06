<template>
	<v-card link :to="toWatchPage" class="app-watch-card">
		<v-img
			:src="item.posterUrl"
			:height="height"
			scale="0.8"
			gradient="0deg, rgba(0,0,0,.85) 0%, rgba(0,0,0,.15) 100%"
			cover
			class="align-end"
		>
			<v-card-title class="d-flex align-center text-white text-body-2">
				<span class="text-capitalize">{{ $t(`watch.type.${item.type}`) }}</span>
				<span class="px-2"> ● </span>
				<span>{{ item.year }}</span>
				<template v-if="item.ratingKinopoisk && item.ratingKinopoisk !== 'null' && item.ratingKinopoisk !== '0.0'">
					<span class="px-2"> ● </span>
					<div class="d-inline-flex align-center">
						<v-icon icon="mdi-star" size="16" class="pe-2" /> {{ item.ratingKinopoisk }}
					</div>
				</template>
			</v-card-title>
		</v-img>
		<v-list class="py-1">
			<v-list-item :prepend-avatar="item.posterUrl">
				<v-list-item-title class="text-body-2">{{ item.nameRu }}</v-list-item-title>
				<template #append>
					<AppWatchActions :item="item" />
				</template>
			</v-list-item>
		</v-list>
	</v-card>
</template>

<script lang="ts" setup>
// @ts-expect-error typescript error
import {computed, defineProps, toRefs} from 'vue'
import type { WatchApiContentItem } from '@/api/watch'
import { RouteNamesEnum } from '@/router/router.types'
import { UTM_SOURCE_KEY, UTM_SOURCE } from '@/const/utm'
import AppWatchActions from '@/components/watch/Actions.vue'
import { useWatchHeight } from '@/composables/useWatchHeight'

interface Props {
	item: WatchApiContentItem
}

const props = defineProps<Props>()
const { item } = toRefs(props)

const { height } = useWatchHeight()

const toWatchPage = {
	name: RouteNamesEnum.watch,
	params: {
		kpid: item.value?.slug || item.value.kinopoiskId,
	},
	query: {
		[UTM_SOURCE_KEY]: UTM_SOURCE.watchcard,
	},
}
</script>
