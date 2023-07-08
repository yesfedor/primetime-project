import { RouteRecordRaw } from 'vue-router'
import { RouteNamesEnum } from '@/router/router.types'
import { LayoutsNamesEnum } from '@/layouts/layouts.types'

export const routes: Array<RouteRecordRaw> = [
  // global
  {
    path: '/',
    name: RouteNamesEnum.home,
    component: () => import(/* webpackChunkName: "global" */ '@/pages/Home.vue'),
    meta: {
      layout: LayoutsNamesEnum.default,
    },
  },

  // auth
  {
    path: '/auth',
    name: RouteNamesEnum.auth,
    component: () => import(/* webpackChunkName: "auth" */ '@/pages/Auth.vue'),
    meta: {
      layout: LayoutsNamesEnum.default,
    },
  },
  {
    path: '/profile',
    name: RouteNamesEnum.profile,
    component: () => import(/* webpackChunkName: "auth" */ '@/pages/Profile.vue'),
    meta: {
      layout: LayoutsNamesEnum.default,
    },
  },

  // navigator
  {
    path: '/navigator/trand',
    name: RouteNamesEnum.navigatorTrand,
    component: () => import(/* webpackChunkName: "navigator" */ '@/pages/navigator/Trand.vue'),
    meta: {
      layout: LayoutsNamesEnum.default,
    },
  },

  // account
  {
    path: '/account/feed',
    name: RouteNamesEnum.feed,
    component: () => import(/* webpackChunkName: "account" */ '@/pages/account/Feed.vue'),
    meta: {
      layout: LayoutsNamesEnum.default,
    },
  },
  {
    path: '/account/history',
    name: RouteNamesEnum.history,
    component: () => import(/* webpackChunkName: "account" */ '@/pages/account/History.vue'),
    meta: {
      layout: LayoutsNamesEnum.default,
    },
  },
  {
    path: '/account/subscriptions',
    name: RouteNamesEnum.subscriptions,
    component: () => import(/* webpackChunkName: "account" */ '@/pages/account/Subscriptions.vue'),
    meta: {
      layout: LayoutsNamesEnum.default,
    },
  },

  // watching
  {
    path: '/film:kpid',
    name: RouteNamesEnum.film,
    component: () => import(/* webpackChunkName: "watch" */ '@/pages/Film.vue'),
    meta: {
      layout: LayoutsNamesEnum.default,
    },
  },
  {
    path: '/watch:kpid',
    name: RouteNamesEnum.watch,
    component: () => import(/* webpackChunkName: "watch" */ '@/pages/Watch.vue'),
    meta: {
      layout: LayoutsNamesEnum.default,
    },
  },
  {
    path: '/trailer:kpid',
    name: RouteNamesEnum.trailer,
    component: () => import(/* webpackChunkName: "watch" */ '@/pages/Trailer.vue'),
    meta: {
      layout: LayoutsNamesEnum.default,
    },
  },

  // staff
  {
    path: '/staff:staff',
    name: RouteNamesEnum.staff,
    component: () => import(/* webpackChunkName: "staff" */ '@/pages/Staff.vue'),
    meta: {
      layout: LayoutsNamesEnum.default,
    },
  },

  // search
  {
    path: '/search/:search',
    name: RouteNamesEnum.search,
    component: () => import(/* webpackChunkName: "search" */ '@/pages/Search.vue'),
    meta: {
      layout: LayoutsNamesEnum.default,
    },
  },
  {
    path: '/search-filter',
    name: RouteNamesEnum.searchFilter,
    component: () => import(/* webpackChunkName: "search" */ '@/pages/SearchFilter.vue'),
    meta: {
      layout: LayoutsNamesEnum.default,
    },
  },

  // errors
  {
    path: '/:pathMatch(.*)',
    name: RouteNamesEnum.error404,
    component: () => import(/* webpackChunkName: "error" */ '@/pages/error/404.vue'),
    meta: {
      layout: LayoutsNamesEnum.default,
    },
  },
]
