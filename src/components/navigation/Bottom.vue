<template>
  <v-bottom-navigation v-if="$vuetify.display.mobile"></v-bottom-navigation>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'AppNavigationBottom',
})

</script>
