<template>
	<v-row class="app-watch-info-table">
		<v-col cols="9" class="d-flex align-center">
			<v-skeleton-loader :loading="isLoading" width="100%" type="list-item" class="bg-background">
				<h1 class="text-h5 text-lg-h4">
					<span class="text-truncate text-capitalize pe-2">{{ $t(`watch.type.${data.type}`) }}</span>
					<span>{{ data.nameRu || data.nameEn }}</span>
				</h1>
			</v-skeleton-loader>
		</v-col>
		<v-col cols="3" class="d-flex align-center justify-end">
			<span v-if="data.ratingAgeLimits" class="text-h5 text-lg-h4">{{ `${data.ratingAgeLimits}+` }}</span>
		</v-col>
		<v-col cols="12">
			<v-divider />
			<v-card width="100%" class="pt-4 pb-0 bg-background">
				<v-card-actions class="d-flex align-center justify-center justify-lg-end pa-0">
					<AppWatchSelectPlayer />
					<AppWatchSubscribeManager :kinopoisk-id="kinopoiskId" show-text />
				</v-card-actions>
			</v-card>
		</v-col>
		<v-col cols="12" class="pt-1">
			<v-skeleton-loader :loading="isLoading" type="article" class="py-3">
				<v-card>
					<v-card-title>{{ $t('watch_info.description') }}</v-card-title>
					<v-card-text>{{ data.description }}</v-card-text>
				</v-card>
			</v-skeleton-loader>
		</v-col>
	</v-row>
</template>

<script lang="ts" setup>
import { defineProps, Ref, toRefs, computed } from 'vue'
import { WatchApiExpandedItem } from '@/api/watch'
// import { useI18n } from 'vue-i18n'
import AppWatchSubscribeManager from '@/components/watch/SubscribeManager.vue'
import AppWatchSelectPlayer from '@/components/watch/SelectPlayer.vue'

interface Props {
	data: WatchApiExpandedItem | false
	isLoading?: boolean
}

const props = defineProps<Props>()
const { data } = toRefs(props) as {
	data: Ref<WatchApiExpandedItem>
	isLoading: Ref<boolean> | undefined
}

const kinopoiskId = computed(() => data.value?.kinopoiskId || '')

// const i18n = useI18n()
</script>
