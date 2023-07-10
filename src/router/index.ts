import { createRouter, createWebHistory } from 'vue-router'
import { routes } from '@/router/routes'
import { layoutMiddleware } from '@/middleware/layout'
import { isAuthMiddleware } from '@/middleware/is-auth'

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
})

router.beforeEach(layoutMiddleware)
router.beforeEach(isAuthMiddleware)

export default router
