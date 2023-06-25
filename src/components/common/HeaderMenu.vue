<template>
  <v-btn
    :append-icon="user.isAuth ? 'mdi-account' : 'mdi-login'"
    color="primary"
    variant="tonal"
    @click="activatorAction"
  >
    <span v-if="user.isAuth">{{ user.data.name }}</span>
    <span v-else>{{ $t('auth.menu.signin') }}</span>
  </v-btn>



  <v-menu
    :close-on-content-click="false"
    location="bottom"
  >
    <template v-slot:activator="{ props }">
      <v-btn
        v-bind="props"
        icon="mdi-dots-vertical"
        color="primary"
        variant="text"
        @click="activatorAction"
      />
    </template>
    <v-list min-width="200px">
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
        <v-btn variant="tonal" class="d-block w-100" @click="logout">
          {{ $t('auth.logout') }}
        </v-btn>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import { useRouter } from 'vue-router'
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
    const authProvider = useAuth()
    const user = useUser()
    const router = useRouter()

    const activatorAction = () => {
      return user.isAuth ? router.push({ name: RouteNamesEnum.profile }) : router.push({ name: RouteNamesEnum.auth })
    }

    return {
      logout: () => authProvider.logout(),
      RouteNamesEnum,
      user: computed(() => user),
      activatorAction,
    }
  },
})
</script>
