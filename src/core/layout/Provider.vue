<template>
  <component :is="route.meta.layoutComponent">
    <slot />
  </component>
</template>

<script lang="ts">
import { useRoute } from 'vue-router'

export default {
  name: 'AppCoreLayoutProvider',
  setup() {
    return {
      route: useRoute(),
    }
  },
}
</script>
