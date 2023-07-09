<template>
  <div class="app-global-scope">
    <AppLoaderDialog />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useThemeSave } from '@/composables/useThemeSave'
import AppLoaderDialog from '@/components/common/LoaderDialog.vue'

export default defineComponent({
  name: 'AppGlobalScope',
  components: {
    AppLoaderDialog,
  },
  setup() {
    useThemeSave()
  },
})
</script>
