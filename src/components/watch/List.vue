<template>
	<v-row>
		<v-col v-if="isShowError" class="text-center">
			<h2>{{ isLoading ? $t('app.loading') : $t('app.no_result') }}</h2>
			<v-progress-circular v-if="isLoading" indeterminate size="32" width="4" class="mt-5" />
		</v-col>
		<template v-else>
			<v-col v-for="item in list" :key="item.id" v-bind="WATCH_CARDS_SIZES">
				<AppWatchCardDefault :item="item" />
			</v-col>
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
}

const props = defineProps<Props>()
const { list, isLoading } = toRefs(props)
const isShowError = computed(() => {
	return isLoading.value || !list.value.length
})
</script>
