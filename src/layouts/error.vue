<template>
  <div>
    error
    {{ error }}
    <slot />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'error',
  props: {
    error: {
      type: String,
    },
  },
})
</script>
