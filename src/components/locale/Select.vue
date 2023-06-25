<template>
  <v-select
    :model-value="currentLocale"
    :items="languageCodes"
    :label="$t('app.locales.label')"
    item-value="key"
    item-title="label"
    return-object
    variant="outlined"
    density="comfortable"
    class="pt-2"
    @update:model-value="changeLanguage"
  />
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useI18n } from 'vue-i18n'

interface ILanguageCodes {
  key: string,
  label: string
}

export default defineComponent({
  name: 'AppLocaleSelect',
  setup() {
    const i18n = useI18n()
    const changeLanguage = (locale: ILanguageCodes) => {
      i18n.locale.value = locale.key
    }
    const languageCodes: ILanguageCodes[] = []
    i18n.availableLocales.forEach((item) => {
      const i18nKey = item.replace('-', '')
      languageCodes.push({
        key: item,
        label: i18n.t(`app.locales.${i18nKey}`),
      })
    })
    const currentLocale = {
      key: i18n.locale,
      label: i18n.t(`app.locales.${i18n.locale.value.replace('-', '')}`),
    }

    return {
      currentLocale,
      languageCodes,
      changeLanguage,
    }
  },
})
</script>
