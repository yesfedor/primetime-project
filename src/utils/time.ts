import { useI18n } from 'vue-i18n'

export function prettyCinemaDuration (duration: string) {
  if (!Number(duration)) return

  const { t } = useI18n()
  const date = new Date(0)

  date.setMinutes(Number(duration))
  const hours = date.getUTCHours()
  if (!hours) {
    return date.getUTCMinutes() + t('trailer.duration.minutes')
  }
  return `${hours}${t('trailer.duration.hours')}:${date.getUTCMinutes()}${t('trailer.duration.minutes')}`
}
