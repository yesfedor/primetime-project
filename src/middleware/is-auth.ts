import type { RouteLocationNormalized, RouteLocationRaw } from 'vue-router'
import { getFailRoute } from '@/router/routes'
import { useAuth } from '@/api/auth'
import { RouteNamesEnum } from '@/router/router.types'

export async function isAuthMiddleware(to: RouteLocationNormalized): Promise<RouteLocationRaw | boolean> {
	const authProvider = useAuth()
	if (!to.meta?.isNeedAuth || authProvider.user.isAuth) {
		return true
	}
	if (to.name === RouteNamesEnum.watch && to.params.kpid) {
		return {
			name: RouteNamesEnum.trailer,
			params: {
				kpid: to.params.kpid,
			},
		}
	}
	return getFailRoute(to)
}
