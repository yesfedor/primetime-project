<template>
  <v-container>
    <h1>{{ $t('home.title') }}</h1>
    <v-btn @click="$router.push({ name: 'watch', params: { kpid: 1 } })">Watch</v-btn>
  </v-container>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'Home',
})
</script>
