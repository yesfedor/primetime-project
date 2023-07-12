import type { RouteLocationNormalized, RouteLocationRaw } from 'vue-router'
import { RouteNamesEnum } from '@/router/router.types'

export async function preventFilmMiddleware(to: RouteLocationNormalized): Promise<RouteLocationRaw | boolean> {
	if (to.name === RouteNamesEnum.film && to.params.kpid) {
		return {
			name: RouteNamesEnum.watch,
			params: {
				kpid: to.params.kpid,
			},
		}
	}
	return true
}
