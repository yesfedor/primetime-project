import { ref, Ref, computed } from 'vue'
import { API_CONFIG } from '@/api/config'

export enum PlayerAlias {
	bazon = 'bazon',
	svetacdn = 'svetacdn',
}

type PlayerAliasStrings = keyof typeof PlayerAlias

export function usePlayer(kinopoiskId: Ref<string> | null) {
	const playerLocaleStorageKey = 'player-alias'
	const playerAliasDefault = PlayerAlias.svetacdn
	const playerAlias = ref(localStorage.getItem(playerLocaleStorageKey) ?? playerAliasDefault) as Ref<PlayerAliasStrings>
	const playerSrc = computed(() => {
		if (!kinopoiskId) {
			return ''
		}
		switch(playerAlias.value) {
			case PlayerAlias.bazon:
				return `//player.svetacdn.in/LDSZJq4uCNvY?kp_id=${kinopoiskId.value}&domain=${API_CONFIG.host}`
			case PlayerAlias.svetacdn:
			default:
				return `https://v1619875985.bazon.site/kp/${kinopoiskId.value}`
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
