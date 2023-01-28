<template>
  <v-app>
    <v-layout :full-height="true" class="layout layout--clear">
      <v-main>
        <slot />
      </v-main>
    </v-layout>
  </v-app>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'clear',
})
</script>
