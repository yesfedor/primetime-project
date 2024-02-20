import { ref, Ref, computed } from 'vue'
import { API_CONFIG } from '@/api/config'

export enum PlayerAlias {
	svetacdn = 'svetacdn',
  voidboost = 'voidboost',
  cdnmovies = 'cdnmovies',
}

type PlayerAliasStrings = keyof typeof PlayerAlias

const playerLocaleStorageKey = 'player-alias'
const playerAliasDefault = PlayerAlias.svetacdn
const playerAlias = ref(localStorage.getItem(playerLocaleStorageKey) ?? playerAliasDefault) as Ref<PlayerAliasStrings>

export function usePlayer(kinopoiskId: Ref<string> | null) {
	const playerSrc = computed(() => {
		if (!kinopoiskId) {
			return ''
		}
		switch(playerAlias.value) {
			case PlayerAlias.svetacdn:
				return `//player.svetacdn.in/LDSZJq4uCNvY?kp_id=${kinopoiskId.value}&domain=${API_CONFIG.host}`
			case PlayerAlias.voidboost:
        return `//voidboost.tv/embed/${kinopoiskId.value}`
      case PlayerAlias.cdnmovies:
        return `https://ugly-turkey.cdnmovies-stream.online/kinopoisk/${kinopoiskId.value}/iframe`
			default:
				return `//player.svetacdn.in/LDSZJq4uCNvY?kp_id=${kinopoiskId.value}&domain=${API_CONFIG.host}`
		}
	})

	const setPlayerAlias = (name: PlayerAliasStrings) => {
		if (!PlayerAlias[name]) {
			name = playerAliasDefault
		}
		localStorage.setItem(playerLocaleStorageKey, name)
		playerAlias.value = name
	}

	return {
		playerAliasDefault,
		playerAlias,
		playerSrc,
		setPlayerAlias,
	}
}
