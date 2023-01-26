import { createRouter, createWebHistory } from 'vue-router'
import { routes } from '@/router/routes'
import { layoutMiddleware } from '@/middlewares/layout'

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
})

router.beforeEach(layoutMiddleware)

export default router
