<template>
	<v-row>
		<v-col v-if="isShowError" cols="12" class="text-center">
			<h2>{{ isLoading ? $t('app.loading') : $t('app.no_result') }}</h2>
			<v-progress-circular v-if="isLoading" indeterminate size="32" width="4" class="mt-5" />
		</v-col>
		<template v-else>
			<v-col v-if="isLoading && useSkeleton" v-bind="bindCardsSizes">
				<v-skeleton-loader
					v-for="id in '12345678'"
					:key="id"
					:loading="isLoading"
					type="card-avatar"
					boilerplate
					class="mb-5"
				/>
			</v-col>
			<template v-else>
				<v-col v-for="item in list" :key="item.id" :class="{ 'd-none': !item.nameRu || !item.posterUrl }" v-bind="bindCardsSizes">
					<AppWatchCardDefault :item="item" />
				</v-col>
			</template>
		</template>
	</v-row>
</template>

<script lang="ts" setup>
// @ts-expect-error typescript error
import { defineProps, toRefs, computed } from 'vue'
import type { WatchApiContentItem } from '@/api/watch'
import { WATCH_CARDS_SIZES } from '@/const/watch'
import AppWatchCardDefault from '@/components/watch/card/Default.vue'

interface Props {
	list: WatchApiContentItem[]
	isLoading?: boolean
	useSkeleton?: boolean
	cardsSizes?: {
		cols?: number
		sm?: number
		md?: number
		lg?: number
		xl?: number
	}
}

const props = defineProps<Props>()
const { list, isLoading, cardsSizes, useSkeleton } = toRefs(props)

const isShowError = computed(() => {
	if (useSkeleton) {
		return false
	}
	return isLoading.value || !list.value.length
})

const bindCardsSizes = computed(() => {
	return Object.assign({}, WATCH_CARDS_SIZES, cardsSizes?.value)
})
</script>
