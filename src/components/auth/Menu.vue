<template>
  <v-menu
    :close-on-content-click="false"
    location="bottom"
    open-on-hover
  >
    <template v-slot:activator="{ props }">
      <v-btn
        :to="user.isAuth ? false : { name: RouteNamesEnum.auth }"
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
      <v-list-item v-else>
        <small>{{ $t('auth.menu.auth_for_watch') }}</small>
      </v-list-item>
      <v-list-item>
        <div class="d-flex justify-space-between align-center">
          {{ $t('app.theme.label') }}
          <AppThemeToggle />
        </div>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script lang="ts">
import { useUser } from '@/api/auth'
import { RouteNamesEnum } from '@/router/router.types'
import { defineComponent } from 'vue'
import AppThemeToggle from '@/components/theme/Toggle.vue'

export default defineComponent({
  name: 'AppAuthMenu',
  components: {
    AppThemeToggle,
  },
  setup() {
    const user = useUser()
    return {
      RouteNamesEnum,
      user,
    }
  },
})
</script>
