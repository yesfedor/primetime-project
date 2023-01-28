<template>
  <div class="app-global-scope"></div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useThemeSave } from '@/composables/useThemeSave'

export default defineComponent({
  name: 'AppGlobalScope',
  setup() {
    useThemeSave()
  },
})
</script>
