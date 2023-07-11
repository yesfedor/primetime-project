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

export interface WatchApiFastSearchHistoryItem {
	id: string
	query: string
}

export interface WatchApiFastSearchHistory {
	count: number
	queries: WatchApiFastSearchHistoryItem[]
}

export interface WatchApiContentItem extends WatchApiFastSearchItem {
	id: string
	ratingAgeLimits: string
}

export interface WatchApiGetUserHistory extends WatchApiFastSearch {
	content: WatchApiContentItem[]
}

export interface WatchApiGetTrands {
	code: number
	content: WatchApiContentItem[]
	total: number
}

export const watchApi = {
	async fastSearch(query: string, jwt: string, clientId: string) {
		try {
			if (!query.length) {
				return createError(null)
			}
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
	async fastSearchHistory(jwt: string, clientId: string) {
		try {
			const result = await axios.get(API_PATH_METHOD + `watch.fastSearchHistory?v=1.0&jwt=${jwt}&client_id=${clientId}`)
			if (result.data?.count) {
				return result.data as WatchApiFastSearchHistory
			}
		} catch (e) {
			return createError(e)
		}
	},
	async fastSearchHistoryDelete(id: string, jwt: string, clientId: string) {
		try {
			await axios.get(API_PATH_METHOD + `watch.fastSearchHistoryDelete?v=1.0&id=${id}&jwt=${jwt}&client_id=${clientId}`)
			return true
		} catch (e) {
			return createError(e)
		}
	},
	async getUserHistory(jwt: string, clientId: string) {
		try {
			const result = await axios.get(API_PATH_METHOD + `watch.getUserHistory?v=1.0&jwt=${jwt}&client_id=${clientId}`)
			if (result.data?.total) {
				return result.data as WatchApiGetUserHistory
			}
		} catch (e) {
			return createError(e)
		}
	},
	async getTrands(filter?: string) {
		try {
			const act = filter ? filter : 'ALL' 
			const result = await axios.get(API_PATH_METHOD + `watch.getTrand?v=1.0&act=${act}`)
			if (result.data?.total) {
				return result.data as WatchApiGetTrands
			}
		} catch(e) {
			return createError(e)
		}
	},
}
