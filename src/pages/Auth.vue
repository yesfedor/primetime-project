<template>
  <v-container>
    <v-btn @click="login">
      Login
    </v-btn>
  </v-container>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useAuth } from '@/api/auth'

export default defineComponent({
  name: 'Auth',
  setup() {
    const authProvider = useAuth()
    const login = async () => {
      const user = await authProvider.login('mail@site.com', 'password')
      debugger
      return user
    }

    return {
      login,
    }
  },
})
</script>
