import type { RouteLocationNormalized } from 'vue-router'
import { LayoutsNamesEnum } from '@/layouts/layouts.types'

export async function layoutMiddleware(route: RouteLocationNormalized): Promise<void> {
  const { layout } = route.meta
  const normalizedLayoutName = layout || LayoutsNamesEnum.default
  const component = await import(`@/layouts/${normalizedLayoutName}.vue`)
  route.meta.layoutComponent = component.default
}
