<template>
	<v-container>
		<v-row>
			<v-col cols="12">
				<h1>Просмотренное пользователями</h1>
			</v-col>
			<v-col>
				<v-divider />
			</v-col>
		</v-row>
		<v-row v-if="!isLoading && viewed.length">
			<v-col
				v-for="{ time, user, trailer } in viewed"
				:key="`${user.uid}-${trailer.kinopoiskId}`"
				cols="12"
				md="6"
				lg="3"
			>
				<v-card :to="{ name: RouteNamesEnum.watch, params: { kpid: trailer.kinopoiskId } }" link>
					<v-img
						:src="trailer.posterUrl"
						:aspect-ratio="4 / 3"
						cover
						gradient="0deg, rgba(0,0,0,.85) 0%, rgba(0,0,0,.15) 100%"
						scale="0.8"
						class="align-end"
					>
						<v-row class="px-4 pb-2">
							<v-col cols="12">
								<h4>{{ getTime(Number(time)) }}</h4>
							</v-col>
						</v-row>
					</v-img>
					<v-card-title>{{ capitalizeFirstLetter($t(`watch.type.${trailer.type}`)) }} - {{ trailer.nameRu }}</v-card-title>
					<v-card-text>{{ user.name }} {{ user.surname }}</v-card-text>
				</v-card>
			</v-col>
		</v-row>
		<v-row v-else-if="isLoading">
			<v-col>
				<h4>Загрузка</h4>
			</v-col>
		</v-row>
		<v-row v-else>
			<v-col>
				<h4>ПУСТО</h4>
			</v-col>
		</v-row>
	</v-container>
</template>

<script lang="ts" setup>
import { ref, Ref, onMounted, computed, onUnmounted } from 'vue'
import { watchApi, WatchApiGAdminViewed } from '@/api/watch'
import { useAuth } from '@/api/auth'
import { capitalizeFirstLetter } from '@/utils/capitalizeFirstLetter.helper'
import { useTimeAgo } from '@vueuse/core'
import { RouteNamesEnum } from '@/router/router.types'

const authProvider = useAuth()

const isLoading = ref(false)
const viewed = ref([]) as Ref<WatchApiGAdminViewed[]>

const loadViewed = async () => {
	isLoading.value = true
	const result = await watchApi.adminViewed(authProvider.getJwt())
	if (result && result.length) {
		viewed.value = result
	} else {
		viewed.value = []
	}
	isLoading.value = false
}

const currentTime = Date.now()
const offsetTime = 604800 * 1000

const getTime = computed(() => {
	return (time: number) => {
		time = time * 1000
		const date = new Date(time)
		if (currentTime - time > offsetTime) {
			return date.toLocaleString('ru-RU')
		}
		return useTimeAgo(date).value
	}
})

const timer = setInterval(() => {
	loadViewed()
}, 5 * 60 * 1000)

onMounted(async () => await loadViewed())
onUnmounted(() => clearInterval(timer))
</script>
