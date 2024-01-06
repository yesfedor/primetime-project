import { IUserResponceData } from '@/api/auth'
import { API_PATH_METHOD } from '@/api/config'
import axios from 'axios'

function createError(e: unknown) {
	if (e) {
		// eslint-disable-next-line
		console.error(e)
	}
	return null
}

export enum WatchContentTypeEnum {
	FILM = 'FILM',
	VIDEO = 'VIDEO',
	TV_SERIES = 'TV_SERIES',
	MINI_SERIES = 'MINI_SERIES',
	TV_SHOW = 'TV_SHOW',
}

type WatchContentType = 'FILM' | 'VIDEO' | 'TV_SERIES' | 'MINI_SERIES' | 'TV_SHOW'

export interface WatchApiFastSearchItem {
	kinopoiskId: string
	nameRu: string
	posterUrl: string
	ratingKinopoisk: string
	type: WatchContentType,
	year: string
}

export interface WatchApiExpandedItem {
	countries: string
	description: string
	endYear: string
	filmLength: string
	genres: string
	id: string
	imdbId: string
	kinopoiskId: string
  slug: string
	nameEn: string
	nameRu: string
	posterUrl: string
	posterUrlPreview: string
	ratingAgeLimits: string
	ratingFilmCritics: string
	ratingFilmCriticsVoteCount: string
	ratingKinopoisk: string
	ratingKinopoiskVoteCount: string
	shortDescription: string
	slogan: string
	startYear: string
	trailer_src: string
	type: string
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
  slug?: string
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

export interface WatchApiGetSubscriptions extends WatchApiFastSearch {
	content: WatchApiContentItem[]
}

export interface WatchApiGetRecommendationsDataByKpid {
	items: WatchApiContentItem[]
	total: number
}

export type WatchApiSubscribeManagerType = 'subscribe' | 'unsubscribe'

export interface WatchApiGetUserRecord {
	kinopoiskId: 	number
	status: WatchApiSubscribeManagerType
	time: number
	uid: number
}

export interface WatchApiGetFacts {
	code: number
	content: {
		id: number
		text: string
	}[]
	total: number
}

export interface WatchApiGetReviewsItem {
	reviewDescription: string
	reviewTitle: string
	reviewType: string
}

export interface WatchApiGetReviews {
	code: number
	items: {
		NEGATIVE: WatchApiGetReviewsItem[]
		NEUTRAL: WatchApiGetReviewsItem[]
		POSITIVE: WatchApiGetReviewsItem[]
		UNKNOWN: WatchApiGetReviewsItem[]
	}
	total: number
}

export interface WatchApiGetStaffItem {
	description: string
	nameEn: string
	nameRu: string
	posterUrl: string
	professionText: string
	staffId: string
}

enum WatchApiGetStaffTypes {
	ACTOR = 'ACTOR',
	DESIGN = 'DESIGN',
	PRODUCER = 'PRODUCER',
	WRITER = 'WRITER'
}

export type WatchApiGetStaffType = keyof typeof WatchApiGetStaffTypes

export interface WatchApiGetStaffByKpid {
	code: number
	staff: {
		ACTOR: WatchApiGetStaffItem[]
		DESIGN: WatchApiGetStaffItem[]
		PRODUCER: WatchApiGetStaffItem[]
		WRITER: WatchApiGetStaffItem[]
	}
}

export interface WatchApiGAdminViewed {
	user: IUserResponceData,
	trailer: WatchApiExpandedItem,
	time: string | number,
}

export interface WatchApiItemsByStaffItem {
  id: string
  kinopoiskId: string
  nameRu: string
  ratingKinopoisk: string
}

export interface WatchApiItemsByStaff {
  code: number
  title: string
  items: WatchApiItemsByStaffItem[]
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
	async getSubscriptions(jwt: string, clientId: string) {
		try {
			const result = await axios.get(API_PATH_METHOD + `watch.getSubscriptions?v=1.0&jwt=${jwt}&client_id=${clientId}`)
			if (result.data?.total) {
				return result.data as WatchApiGetSubscriptions
			}
		} catch (e) {
			return createError(e)
		}
	},
	async getUserRecord(kpid: number, jwt: string, clientId: string) {
		try {
			const result = await axios.get(API_PATH_METHOD + `watch.getUserRecord?v=1.0&kpid=${kpid}&jwt=${jwt}&client_id=${clientId}`)
			if (result.data?.uid) {
				return result.data as WatchApiGetUserRecord
			}
		} catch (e) {
			return createError(e)
		}
	},
	async subscribeManager(act: WatchApiSubscribeManagerType, kpid: number, jwt: string, clientId: string) {
		try {
			const result = await axios.get(API_PATH_METHOD + `watch.subscribeManager?v=1.0&act=${act}&kpid=${kpid}&jwt=${jwt}&client_id=${clientId}`)
			if (result.data?.uid) {
				return result.data as WatchApiGetUserRecord
			}
		} catch (e) {
			return createError(e)
		}
	},
	async getTrailerData(kpid: string) {
		try {
			const result = await axios.get(API_PATH_METHOD + `watch.getTrailerData?v=1.0&kpid=${kpid}`)
			if (result.data?.kinopoiskId) {
				return result.data as WatchApiExpandedItem
			}
		} catch(e) {
			return createError(e)
		}
	},
	async getRecommendationsDataByKpid(kpid: string) {
		try {
			const result = await axios.get(API_PATH_METHOD + `watch.getRecommendationsDataByKpid?v=1.0&kpid=${kpid}`)
			if (result.data?.total) {
				return result.data as WatchApiGetRecommendationsDataByKpid
			}
		} catch (e) {
			return createError(e)
		}
	},
	async getFacts(kpid: string) {
		try {
			const result = await axios.get(API_PATH_METHOD + `watch.getFacts?v=1.0&kpid=${kpid}`)
			if (result.data?.total) {
				return result.data as WatchApiGetFacts
			}
		} catch (e) {
			return createError(e)
		}
	},
	async getReviews(kpid: string) {
		try {
			const result = await axios.get(API_PATH_METHOD + `watch.getReviews?v=1.0&kpid=${kpid}`)
			if (result.data?.total) {
				return result.data as WatchApiGetReviews
			}
		} catch (e) {
			return createError(e)
		}
	},
	async getStaffByKpid(kpid: string) {
		try {
			const result = await axios.get(API_PATH_METHOD + `watch.getStaffByKpid?v=1.0&kpid=${kpid}`)
			if (result.data?.code) {
				return result.data as WatchApiGetStaffByKpid
			}
		} catch (e) {
			return createError(e)
		}
	},
	async getDataByKpid(kpid: string, jwt: string) {
		try {
			const result = await axios.get(API_PATH_METHOD + `watch.getDataByKpid?v=1.0&kpid=${kpid}&jwt=${jwt}`)
			if (result.data?.id) {
				return result.data as WatchApiExpandedItem
			}
		} catch (e) {
			return createError(e)
		}
	},
  async getDataBySlug(slug: string, jwt: string) {
    try {
      const result = await axios.get(API_PATH_METHOD + `watch.getDataBySlug?v=1.0&slug=${slug}&jwt=${jwt}`)
      if (result.data?.id) {
        return result.data as WatchApiExpandedItem
      }
    } catch (e) {
      return createError(e)
    }
  },
  async getNameByStaffId(staff: string) {
    try {
      const result = await axios.get(API_PATH_METHOD + `watch.getNameByStaffId?v=1.0&staff=${staff}`)
      if (result.data?.title) {
        return result.data as WatchApiItemsByStaff
      }
    } catch (e) {
      return createError(e)
    }
  },
	async adminViewed(jwt: string) {
		try {
			const result = await axios.get(API_PATH_METHOD + `watch.adminViewed?v=1.0&jwt=${jwt}`)
			if (result.data?.length) {
				return result.data as WatchApiGAdminViewed[]
			}
		} catch (e) {
			return createError(e)
		}
	},
}
