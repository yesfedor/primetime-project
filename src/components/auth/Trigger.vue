<template>
	<v-btn
    :append-icon="mdAndUp ? icon : undefined"
    :icon="mdAndUp ? false : true"
    color="primary"
    variant="text"
    @click="activatorAction"
  >
    <v-icon v-if="!mdAndUp" :icon="icon" />
    <span v-if="user.isAuth" class="d-none d-md-inline">{{ user.data.name }}</span>
    <span v-else class="d-none d-md-inline">{{ $t('auth.menu.signin') }}</span>
  </v-btn>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { useDisplay } from 'vuetify/lib/framework.mjs'
import { useUser } from '@/api/auth'
import { RouteNamesEnum } from '@/router/router.types'
import { useRouter } from 'vue-router'

const userProvider = useUser()
const user = computed(() => userProvider)
const router = useRouter()
const display = useDisplay()
const icon = computed(() => user.value.isAuth ? 'mdi-account' : 'mdi-login')
const activatorAction = () => {
	return user.value.isAuth ? router.push({ name: RouteNamesEnum.profile }) : router.push({ name: RouteNamesEnum.auth })
}
const mdAndUp = computed(() => {
  return display.mdAndUp.value
})
</script>
