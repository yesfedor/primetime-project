import { API_PATH_METHOD } from '@/api/config'
import axios from 'axios'

function createError(e: unknown) {
  // eslint-disable-next-line
	console.error(e)
	return null
}

type WatchContentType = 'FILM' | 'VIDEO' | 'TV_SERIES' | 'MINI_SERIES' | 'TV_SHOW'

export interface WatchApiFastSearchItem {
	kinopoiskId: number
	nameRu: string
	posterUrl: string
	ratingKinopoisk: string
	type: WatchContentType,
	year: string
}

export interface WatchApiFastSearch {
	code: number
	content: WatchApiFastSearchItem[]
	total: number
}

export const watchApi = {
	async fastSearch(query: string, jwt: string, clientId: string) {
		try {
			const result = await axios.get(API_PATH_METHOD + `watch.fastSearch?v=1.0&query=${query}&jwt=${jwt}&client_id=${clientId}`)
			if (result?.data.code === 200 && result.data.total) {
				return result.data as WatchApiFastSearch
			}
			return {
				code: 200,
				content: [],
				total: 0,
			}
		} catch (e) {
			return createError(e)
		}
	},
}
