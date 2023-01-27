<template>
  <div class="layout layout--clear">
    <slot />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'clear',
})
</script>
