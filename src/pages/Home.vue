<template>
  <v-container fluid>
    <v-row>
      <v-col cols="12">
        <h1>{{ $t('home.title') }}</h1>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'Home',
})
</script>
