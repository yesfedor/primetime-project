import type { RouteLocationNormalized, RouteLocationRaw } from 'vue-router'
import { getFailRoute } from '@/router/routes'
import { useAuth } from '@/api/auth'

export async function isAuthMiddleware(to: RouteLocationNormalized): Promise<RouteLocationRaw | boolean> {
	const authProvider = useAuth()
	if (!to.meta?.isNeedAuth || authProvider.user.isAuth) {
		return true
	}
	return getFailRoute(to)
}
