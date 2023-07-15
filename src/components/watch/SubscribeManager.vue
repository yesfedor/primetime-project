<template>
	<v-btn
		v-if="authProvider.user.isAuth"
		:loading="fetchLoading"
		:disabled="fetchLoading"
		:variant="variant"
		:icon="!showText"
		:color="color"
		v-bind="props"
		@click.stop="managerAction"
	>
		<span v-if="showText">
			{{ $t(`subscriptions.manager.action.${managerStatusAlt}`) }}
		</span>
		<v-icon v-else :icon="managerStatusIcon" />		
	</v-btn>
</template>

<script lang="ts" setup>
// @ts-expect-error typescript error
import { defineProps, toRefs, ref, computed, onMounted, watch } from 'vue'
import { watchApi, WatchApiSubscribeManagerType } from '@/api/watch'
import { useAuth } from '@/api/auth'

interface Props {
	kinopoiskId: string
	showText?: boolean
}

const props = defineProps<Props>()
const { kinopoiskId, showText } = toRefs(props)

const managerStatus = ref<WatchApiSubscribeManagerType>('unsubscribe')

const isSubscribe = computed(() => {
	return managerStatus.value === 'subscribe'
})

const managerStatusAlt = computed((): WatchApiSubscribeManagerType => {
	return isSubscribe.value ? 'unsubscribe' : 'subscribe'
})

const managerStatusIcon = computed(() => {
	if (showText.value) {
		return undefined
	}
	return isSubscribe.value ? 'mdi-heart' : 'mdi-heart-outline'
})

const authProvider = useAuth()
const fetchLoading = ref(false)
const fetchStatus = async () => {
	if (!kinopoiskId.value) {
		return
	}
	fetchLoading.value = true
	const result = await watchApi.getUserRecord(Number(kinopoiskId.value), authProvider.getJwt(), await authProvider.getClientId())
	if (result?.kinopoiskId) {
		managerStatus.value = result.status
	}
	fetchLoading.value = false
}

watch(kinopoiskId, () => {
	fetchStatus()
})

const managerAction = async () => {
	fetchLoading.value = true
	const result = await watchApi.subscribeManager(managerStatusAlt.value, Number(kinopoiskId.value), authProvider.getJwt(), await authProvider.getClientId())
	if (result?.kinopoiskId) {
		managerStatus.value = result.status
	}
	setTimeout(() => {
		fetchLoading.value = false
	}, 500)
}

const variant = computed(() => {
	if (showText) {
		return isSubscribe.value ? 'outlined' : 'flat'
	}
	return 'text'
})

const color = computed(() => {
	return 'primary'
})

onMounted(() => {
	fetchStatus()
})
</script>
