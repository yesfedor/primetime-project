import { onMounted, reactive, watch } from 'vue'

export enum navigationDrawerWidthEnum {
  iconWithLabel = '256',
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

const navigationDrawer = reactive<INavigationDrawer>({
  show: false,
  width: navigationDrawerWidthEnum.iconWithLabel,
})

export function useNavigationDrawer() {
  const storageKey = 'NavigationDrawer'
  const storageKeyWidth = 'NavigationDrawerWidth'

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

  onMounted(() => {
    const savedNavigationDrawer = localStorage.getItem(storageKey)
    const savedNavigationDrawerWidth = localStorage.getItem(storageKeyWidth) || navigationDrawerWidthEnum.iconWithLabel
    if (navigationDrawer.width !== savedNavigationDrawerWidth) {
      navigationDrawer.width = savedNavigationDrawerWidth
    }
    if (savedNavigationDrawer === navigationDrawerStorageEnum.show) {
      show()
    } else {
      hide()
    }
  })

  return {
    navigationDrawer,
    show,
    hide,
    toggle,
    toggleWidth,
  }
}
