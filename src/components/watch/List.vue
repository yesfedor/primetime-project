<template>
	<v-row>
		<v-col v-for="item in list" :key="item.id" v-bind="WATCH_CARDS_SIZES">
			<AppWatchCardDefault :item="item" />
		</v-col>
	</v-row>
</template>

<script lang="ts" setup>
import { defineProps, toRefs } from 'vue'
import type { WatchApiContentItem } from '@/api/watch'
import { WATCH_CARDS_SIZES } from '@/const/watch'
import AppWatchCardDefault from '@/components/watch/card/Default.vue'

interface Props {
	list: WatchApiContentItem[]
}

const props = defineProps<Props>()
const { list } = toRefs(props)
</script>
