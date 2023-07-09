<template>
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
        class="order-2"
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

<script lang="ts" setup>
import { useAuth, useUser } from '@/api/auth'
import AppThemeToggle from '@/components/theme/Toggle.vue'
import AppLocaleSelect from '@/components/locale/Select.vue'

const authProvider = useAuth()
const user = useUser()
const logout = () => authProvider.logout()
</script>
