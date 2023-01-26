import type { VueElement } from 'vue'
import type { LayoutsNamesEnum } from '@/layouts/layouts.types'


declare module 'vue-router' {
  interface RouteMeta {
    layout?: LayoutsNamesEnum
    layoutComponent?: VueElement
  }
}


export enum RouteNamesEnum {
  home = 'home',
  error404 = 'error404',
}
