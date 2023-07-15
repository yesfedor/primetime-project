import { createRouter, createWebHistory } from 'vue-router'
import { routes } from '@/router/routes'
import { layoutMiddleware } from '@/middleware/layout'
import { isAuthMiddleware } from '@/middleware/is-auth'
import { preventFilmMiddleware } from '@/middleware/prevent-film'

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
  scrollBehavior() {
    return {
      top: 0,
    }
  },
})

router.beforeEach(layoutMiddleware)
router.beforeEach(preventFilmMiddleware)
router.beforeEach(isAuthMiddleware)

export default router
