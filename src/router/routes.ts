import { RouteRecordRaw } from 'vue-router'

enum LayoutTypes {
  default = 'default',
  clear = 'clear',
  error = 'error',
}

export const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'home',
    component: () => import(/* webpackChunkName: "home" */ '../views/Home.vue'),
    meta: {
      layout: LayoutTypes.default,
    },
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'error404',
    component: () => import(/* webpackChunkName: "home" */ '../views/error/404.vue'),
    meta: {
      layout: LayoutTypes.clear,
    },
  },
]
