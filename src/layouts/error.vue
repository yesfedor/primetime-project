<template>
  <div>
    error
    {{ error }}
    <router-view />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'error',
  props: {
    error: {
      type: Object,
    },
  },
})
</script>
