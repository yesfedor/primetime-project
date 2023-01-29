import { createI18n } from 'vue-i18n'
import { i18nLanguagesEnum } from '@/plugins/i18n/i18n.types'
import { pluralRules } from '@/utils/pluralRules.helper'
import ruRuMessages from '@/locales/ru-RU'
import enUsMessages from '@/locales/en-US'

type MessageSchema = typeof ruRuMessages

export const i18n = createI18n<[MessageSchema], i18nLanguagesEnum.ruRU | i18nLanguagesEnum.enUS>({
  sync: true,
  legacy: false,
  locale: i18nLanguagesEnum.ruRU,
  fallbackLocale: i18nLanguagesEnum.ruRU,
  pluralizationRules: {
    [i18nLanguagesEnum.ruRU]: pluralRules,
  },
  messages: {
    [i18nLanguagesEnum.ruRU]: ruRuMessages,
    [i18nLanguagesEnum.enUS]: enUsMessages,
  },
})
