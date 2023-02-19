import { useEventBus } from '@vueuse/core'

export enum IGlobalBusKeys {
  auth = 'auth'
}

export const $bus = useEventBus<string>('global')
