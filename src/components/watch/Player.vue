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
            :style="`filter: brightness(${playerBrightness})`"
					></iframe>
				</v-responsive>
        <v-item-group class="px-6 my-4">
            <v-row align="center">
              <v-btn
                variant="text"
                icon="mdi-brightness-6"
                @click="playerBrightnessExpanded = !playerBrightnessExpanded"
              />
              <v-slide-x-transition mode="in-out" >
                <v-slider
                  v-show="playerBrightnessExpanded"
                  v-model="playerBrightness"
                  class="align-center justify-center"
                  :min="0"
                  :max="2"
                  :step="0.05"
                  hide-details
                  thumb-label
                />
              </v-slide-x-transition>
            </v-row>
        </v-item-group>
			</v-col>
		</v-row>
	</v-responsive>
</template>

<script lang="ts" setup>
import {defineProps, ref, toRefs} from 'vue'
import { usePlayer } from '@/composables/usePlayer'

interface Props {
	kinopoiskId: string
	isLoading?: boolean
}

const props = defineProps<Props>()
const { kinopoiskId, isLoading } = toRefs(props)

const { playerSrc } = usePlayer(kinopoiskId)

const playerBrightness = ref(1)
const playerBrightnessExpanded = ref(false)
</script>

<style lang="scss">
.app-watch-player {
	.v-skeleton-loader__bone {
		height: 100%;
	}
}
</style>
