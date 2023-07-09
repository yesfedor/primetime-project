<template>
  <v-btn
    :to="{ name: 'home' }"
    :exact="false"
    :ripple="false"
    variant="text"
    class="app-logo px-0"
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
  font-size: lib-font-size(18) !important;
  font-weight: map-get($font-weights, 'meduim');
  font-family: $font-montserrat-alt !important;
  letter-spacing: 4px !important;

  .v-btn__overlay, &:hover .v-btn__overlay {
    opacity: 0;
  }

  @media #{map-get($display-breakpoints, "md-and-up")} {
    font-size: lib-font-size(20) !important;
  }
}
</style>
