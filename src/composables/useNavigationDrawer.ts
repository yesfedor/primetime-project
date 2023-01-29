import { onMounted, reactive, watch } from 'vue'
import { RouteRecordName, useRouter } from 'vue-router'
import { RouteNamesEnum } from '@/router/router.types'

export enum navigationDrawerWidthEnum {
  iconWithLabel = '240',
  icon = '68'
}

enum navigationDrawerStorageEnum {
  show = '1',
  hide = '0',
}

interface INavigationDrawer {
  show: boolean,
  width: string | navigationDrawerWidthEnum
}

const storageKey = 'NavigationDrawer'
const storageKeyWidth = 'NavigationDrawerWidth'
const navigationDrawer = reactive<INavigationDrawer>({
  show: true,
  width: navigationDrawerWidthEnum.iconWithLabel,
})

export function useNavigationDrawer() {
  const router = useRouter()
  const minWidthRouteList: (RouteRecordName | string | null | undefined)[] = [
    RouteNamesEnum.film,
    RouteNamesEnum.watch,
    RouteNamesEnum.trailer,
  ]

  const show = () => {
    navigationDrawer.show = true
  }
  const hide = () => {
    navigationDrawer.show = false
  }
  const toggle = () => {
    navigationDrawer.show = !navigationDrawer.show
  }
  const toggleWidth = () => {
    if (navigationDrawer.width === navigationDrawerWidthEnum.iconWithLabel) {
      navigationDrawer.width = navigationDrawerWidthEnum.icon
      localStorage.setItem(storageKeyWidth, navigationDrawerWidthEnum.icon)
    } else {
      navigationDrawer.width = navigationDrawerWidthEnum.iconWithLabel
      localStorage.setItem(storageKeyWidth, navigationDrawerWidthEnum.iconWithLabel)
    }
  }

  watch(navigationDrawer, () => {
    if (navigationDrawer.show) {
      localStorage.setItem(storageKey,navigationDrawerStorageEnum.show)
    } else {
      localStorage.setItem(storageKey, navigationDrawerStorageEnum.hide)
    }
  })

  const initWidth = () => {
    const savedNavigationDrawerWidth = localStorage.getItem(storageKeyWidth) || navigationDrawerWidthEnum.iconWithLabel
    if (navigationDrawer.width !== savedNavigationDrawerWidth) {
      navigationDrawer.width = savedNavigationDrawerWidth
    }
  }
  const resetWidth = () => {
    if (minWidthRouteList.includes(router.currentRoute.value.name)) {
      navigationDrawer.width = navigationDrawerWidthEnum.icon
    } else {
      initWidth()
    }
  }

  watch(router.currentRoute, () => {
    resetWidth()
  })

  onMounted(() => {
    const savedNavigationDrawer = localStorage.getItem(storageKey) || navigationDrawerStorageEnum.show
    initWidth()
    if (savedNavigationDrawer === navigationDrawerStorageEnum.show) {
      show()
    } else {
      hide()
    }

    resetWidth()
  })

  return {
    navigationDrawer,
    show,
    hide,
    toggle,
    toggleWidth,
  }
}
