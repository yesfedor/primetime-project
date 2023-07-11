<template>
	<v-card link :to="toWatchPage">
		<v-img
			:src="item.posterUrl"
			class="align-end"
			gradient="0deg, rgba(0,0,0,.85) 0%, rgba(0,0,0,.15) 100%"
			height="260px"
			cover
		>
			<v-card-title class="d-flex align-center text-primary text-body-1">
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
		<v-list>
			<v-list-item :prepend-avatar="item.posterUrl">
				<v-list-item-title>{{ item.nameRu }}</v-list-item-title>
				<template #append>
					<AppWatchActions :item="item" />
				</template>
			</v-list-item>
		</v-list>
	</v-card>
</template>

<script lang="ts" setup>
// @ts-expect-error typescript error
import { defineProps, toRefs } from 'vue'
import type { WatchApiContentItem } from '@/api/watch'
import { RouteNamesEnum } from '@/router/router.types'
import { UTM_SOURCE_KEY, UTM_SOURCE } from '@/const/utm'
import AppWatchActions from '@/components/watch/Actions.vue'

interface Props {
	item: WatchApiContentItem
}

const props = defineProps<Props>()
const { item } = toRefs(props)

const toWatchPage = {
	name: RouteNamesEnum.watch,
	params: {
		kpid: item.value.kinopoiskId,
	},
	query: {
		[UTM_SOURCE_KEY]: UTM_SOURCE.watchcard,
	},
}
</script>
