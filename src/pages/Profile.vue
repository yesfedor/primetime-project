<template>
  <v-container fluid>Profile</v-container>
</template>

<script lang="ts" setup>
import { useI18n } from 'vue-i18n'
import { useTitle } from '@vueuse/core'

const i18n = useI18n()
useTitle(i18n.t('search.title'))
</script>
