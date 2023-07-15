<template>
	<v-responsive :aspect-ratio="16 / 9">
		<v-row class="app-watch-player">
			<v-col cols="12">
				<v-responsive :aspect-ratio="16 / 9">
					<v-skeleton-loader v-if="isLoading" class="fill-height" />
					<iframe
						v-else
						:src="playerSrc"
						width="100%"
						height="100%"
						class="app-watch-player__frame"
						allowfullscreen
						title="Player"
						frameborder="0"
					></iframe>
				</v-responsive>
			</v-col>
		</v-row>
	</v-responsive>
</template>

<script lang="ts" setup>
import { defineProps, toRefs } from 'vue'
import { usePlayer } from '@/composables/usePlayer'

interface Props {
	kinopoiskId: string
	isLoading?: boolean
}

const props = defineProps<Props>()
const { kinopoiskId, isLoading } = toRefs(props)

const { playerSrc } = usePlayer(kinopoiskId)
</script>

<style lang="scss">
.app-watch-player {
	.v-skeleton-loader__bone {
		height: 100%;
	}
}
</style>