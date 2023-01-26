import { createI18n } from 'vue-i18n'
import { i18nLanguagesEnum } from '@/plugins/i18n.types'
import { pluralRules } from '@/utils/pluralRules.helper'
import ruRuMessages from '@/i18n/ru-RU'
import enUsMessages from '@/i18n/en-US'

export const i18n = createI18n({
  sync: true,
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
