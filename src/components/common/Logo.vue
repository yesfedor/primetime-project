<template>
  <v-btn
    :to="{ name: 'home' }"
    :exact="false"
    :ripple="false"
    variant="text"
    class="app-logo"
  >
    {{ $t('app.logo.title') }}
  </v-btn>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'AppLogo',
})
</script>

<style lang="scss">
.app-logo {
  font-size: 1.25rem;
  font-weight: 700;
  .v-btn__overlay, &:hover .v-btn__overlay {
    opacity: 0;
  }
}
</style>
