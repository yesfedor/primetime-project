<template>
  <v-container fluid></v-container>
</template>

<script lang="ts" setup>
import { useI18n } from 'vue-i18n'
import { useTitle } from '@vueuse/core'
const { t } = useI18n()
useTitle(t('home.seo_title'))
</script>
