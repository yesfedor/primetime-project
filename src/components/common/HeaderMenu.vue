<template>
  <v-menu
    :close-on-content-click="false"
    location="bottom"
    open-on-hover
  >
    <template v-slot:activator="{ props }">
      <v-btn
        v-bind="props"
        :append-icon="user.isAuth ? 'mdi-account' : 'mdi-login'"
        color="primary"
        variant="text"
      >
        <span v-if="user.isAuth">{{ user.data.name }}</span>
        <span v-else>{{ $t('auth.menu.signin') }}</span>
      </v-btn>
    </template>
    <v-list min-width="200px">
      <v-list-item v-if="user.isAuth" :to="{ name: RouteNamesEnum.profile }">
        {{ `${user.data.name} ${user.data.surname}` }}
      </v-list-item>
      <v-list-item v-else :to="{ name: RouteNamesEnum.auth }">
        <small>{{ $t('auth.menu.auth_for_watch') }}</small>
      </v-list-item>
      <v-list-item>
        <div class="d-flex justify-space-between align-center">
          {{ $t('app.theme.label') }}
          <AppThemeToggle />
        </div>
      </v-list-item>
      <v-list-item>
        <AppLocaleSelect />
      </v-list-item>
      <v-list-item v-if="user.isAuth">
        <v-btn class="d-block w-100" @click="logout">
          {{ $t('auth.logout') }}
        </v-btn>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import { useAuth, useUser } from '@/api/auth'
import { RouteNamesEnum } from '@/router/router.types'
import AppThemeToggle from '@/components/theme/Toggle.vue'
import AppLocaleSelect from '@/components/locale/Select.vue'

export default defineComponent({
  name: 'AppHeaderMenu',
  components: {
    AppThemeToggle,
    AppLocaleSelect,
  },
  setup() {
    const auth = useAuth()
    const user = useUser()
    return {
      logout: () => auth.logout(),
      RouteNamesEnum,
      user: computed(() => user),
    }
  },
})
</script>
