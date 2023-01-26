import { RouteRecordRaw } from 'vue-router'
import { RouteNamesEnum } from '@/router/router.types'
import { LayoutsNamesEnum } from '@/layouts/layouts.types'

export const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: RouteNamesEnum.home,
    component: () => import(/* webpackChunkName: "home" */ '@/pages/Home.vue'),
    meta: {
      layout: LayoutsNamesEnum.default,
    },
  },
  {
    path: '/:pathMatch(.*)*',
    name: RouteNamesEnum.error404,
    component: () => import(/* webpackChunkName: "error" */ '@/pages/error/404.vue'),
    meta: {
      layout: LayoutsNamesEnum.error,
    },
  },
]
