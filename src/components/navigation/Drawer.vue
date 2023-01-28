<template>
  <v-navigation-drawer
    v-if="!$vuetify.display.mobile"
    :model-value="navigationDrawer.show"
    :width="navigationDrawer.width"
    :touchless="false"
    disable-resize-watcher
    disable-route-watcher
    class="border-none"
  >
    <v-list
      :lines="false"
      density="default"
      nav
    >
      <template
        v-for="(item, indexMenu) in menuItems"
      >
        <v-list-subheader
          v-if="item.subtitle && navigationDrawerWidthEnum.iconWithLabel === navigationDrawer.width"
          :key="indexMenu"
        >
          {{ item.subtitle }}
        </v-list-subheader>
        <v-list-item
          v-for="(link, indexLink) in item.links"
          :key="indexLink + '-' + indexMenu"
          :value="link"
          exact
          :to="link.to"
          color="primary"
        >
          <template v-if="navigationDrawerWidthEnum.iconWithLabel === navigationDrawer.width" v-slot:prepend>
            <div class="d-flex justify-center me-8">
              <v-icon color="primary" :icon="link.icon"></v-icon>
            </div>
          </template>
          <v-list-item-title
            color="primary"
          >
            <template v-if="navigationDrawerWidthEnum.iconWithLabel === navigationDrawer.width">
              {{ link.label }}
            </template>
            <div v-else class="d-flex justify-center">
              <v-icon color="primary" :icon="link.icon"></v-icon>
            </div>
          </v-list-item-title>
        </v-list-item>
      </template>
    </v-list>
  </v-navigation-drawer>
</template>

<script lang="ts">
import { defineComponent, reactive } from 'vue'
import { useNavigationDrawer, navigationDrawerWidthEnum } from '@/composables/useNavigationDrawer'

export default defineComponent({
  name: 'AppNavigationDrawer',
  setup() {
    const { navigationDrawer } = useNavigationDrawer()
    const menuItems = reactive([
      {
        subtitle: '',
        links: [
          {
            icon: 'mdi-home',
            label: 'Home',
            to: { name: 'home' },
          },
        ],
      },
    ])

    return {
      navigationDrawerWidthEnum,
      navigationDrawer,
      menuItems,
    }
  },
})
</script>
