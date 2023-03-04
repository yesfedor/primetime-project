<template>
  <v-bottom-navigation
    v-if="$vuetify.display.mobile"
    grow
    class="app-navigation-bottom"
  >
    <v-btn
      v-for="link in menuItems"
      :key="link.icon"
      :value="link.to"
      :to="link.to"
      class="app-navigation-bottom__item"
    >
      <v-icon>{{ link.icon }}</v-icon>
    </v-btn>
  </v-bottom-navigation>
</template>

<script lang="ts">
import { RouteNamesEnum } from '@/router/router.types'
import { defineComponent, reactive } from 'vue'
import { useI18n } from 'vue-i18n'

export default defineComponent({
  name: 'AppNavigationBottom',
  setup() {
    const { t } = useI18n()
    const menuItems = reactive([
      {
        icon: 'mdi-home-variant-outline',
        label: t('navigation.home'),
        to: { name: RouteNamesEnum.home },
      },
      {
        icon: 'mdi-fire',
        label: t('navigation.trand'),
        to: { name: RouteNamesEnum.navigatorTrand },
      },
      {
        icon: 'mdi-compass',
        label: t('navigation.searchFilter'),
        to: { name: RouteNamesEnum.searchFilter },
      },
      {
        icon: 'mdi-new-box',
        label: t('navigation.feed'),
        to: { name: RouteNamesEnum.feed },
      },
      {
        icon: 'mdi-youtube-subscription',
        label: t('navigation.subscription'),
        to: { name: RouteNamesEnum.subscriptions },
      },
    ])
    return {
      menuItems,
    }
  },
})
</script>

<style lang="scss">
.app-navigation-bottom {
  &__item {
    min-width: 56px !important;
  }
}
</style>
