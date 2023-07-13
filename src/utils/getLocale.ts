import { useI18n } from 'vue-i18n'

interface IEnumLocale {
  [key: string]: string
  ru: string
  en: string
}

const localeRegex = /[a-z]*/
export const aviableLocales: IEnumLocale = {
  'ru': 'Ru',
  'en': 'En',
}

export function getLocale(): string {
  const i18n = useI18n()
  const currentLocale = i18n.locale.value.match(localeRegex)

  if (currentLocale?.length) {
    return aviableLocales[currentLocale[0]]
  }
  return ''
}
