import type { VueElement } from 'vue'
import type { LayoutsNamesEnum } from '@/layouts/layouts.types'

declare module 'vue-router' {
  interface RouteMeta {
    layout?: LayoutsNamesEnum
    layoutComponent?: VueElement
  }
}

export enum RouteNamesEnum {
  // global
  home = 'home',
  
  // auth
  auth = 'auth',
  profile = 'profile',

  // navigator
  navigatorTrand = 'navigatorTrand',

  // account
  feed = 'feed',
  history = 'history',
  subscriptions = 'subscriptions',
  
  // watching
  film = 'film',
  watch = 'watch',
  trailer = 'trailer',

  // staff
  staff = 'staff',

  // search
  search = 'search',
  searchFilter = 'searchFilter',

  // errors
  error404 = 'error404',
}
