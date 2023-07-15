import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { RouteNamesEnum } from '@/router/router.types'

export function useWatchHeight() {
	const route = useRoute()
	const height = computed(() => route.name === RouteNamesEnum.watch ? '200px' : '260px')
	return {
		height,
	}
}