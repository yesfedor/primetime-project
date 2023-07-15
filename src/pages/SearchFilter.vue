<template>
  <v-container fluid>SearchFilter</v-container>
</template>

<script lang="ts" setup>
import { useI18n } from 'vue-i18n'
import { useTitle } from '@vueuse/core'

const { t } = useI18n()
useTitle(t('search.title'))
</script>
