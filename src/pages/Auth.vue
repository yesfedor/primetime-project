<template>
  <v-container>
    <v-row class="mt-3 mt-lg-5">
      <v-col cols="12" class="text-center">
        <h1>{{ $t('auth.title') }}</h1>
      </v-col>
    </v-row>
    <v-row class="d-flex justify-center align-center mt-3 mt-lg-5">
      <v-col cols="12" md="8" lg="4" xl="3" class="bg-surface">
        <v-row class="px-1">
          <template v-if="action === 'login'">
            <v-col cols="12" class="pt-4 pb-0">
              <v-text-field
                v-model="loginData.username"
                :label="$t('auth.email')"
                prepend-icon="mdi-email"
                clearable
              />
            </v-col>
            <v-col cols="12" class="pt-1 pb-0">
              <v-text-field
                v-model="loginData.password"
                :label="$t('auth.password')"
                :prepend-icon="isPasswordVisible ? 'mdi-eye' : 'mdi-eye-off'"
                :type="isPasswordVisible ? 'text' : 'password'"
                clearable
                @click:prepend="isPasswordVisible = !isPasswordVisible"
              />
            </v-col>
          </template>
          <template v-if="action === 'register'">
            <v-col cols="12" class="pt-4 pb-0">
              <v-text-field
                v-model="loginData.username"
                :label="$t('auth.email')"
                prepend-icon="mdi-email"
                clearable
              />
            </v-col>
            <v-col cols="12" class="pt-1 pb-0">
              <v-text-field
                v-model="loginData.password"
                :label="$t('auth.password')"
                :prepend-icon="isPasswordVisible ? 'mdi-eye' : 'mdi-eye-off'"
                :type="isPasswordVisible ? 'text' : 'password'"
                clearable
                @click:prepend="isPasswordVisible = !isPasswordVisible"
              />
            </v-col>
          </template>
          <v-col cols="12" class="d-flex align-center justify-space-around pt-1 pb-4">
            <v-btn
              :rounded="4"
              :variant="action === 'register' && 'text' || 'tonal'"
              @click="buttonHandler('login')"
            >
            {{ action === 'login' ? $t('auth.next') : $t('auth.login') }}
            </v-btn>
            <v-btn
              :rounded="4"
              :variant="action === 'login' && 'text' || 'tonal'"
              @click="buttonHandler('register')"
            >

              {{ action === 'register' ? $t('auth.next') : $t('auth.register') }}
            </v-btn>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { IUserResponceGender, useAuth } from '@/api/auth'
import { defineComponent, ref, reactive } from 'vue'

type TActions = 'login' | 'register'

export default defineComponent({
  name: 'Auth',
  setup() {
    const auth = useAuth()
    const isPasswordVisible = ref(false)
    const loginData = reactive({
      username: '',
      password: '',
    })
    const registerData = reactive({
      name: '',
      surname: '',
      email: '',
      gender: IUserResponceGender.male,
      password: '',
    })
    const action = ref<TActions>('login')
    const setAction = (newAction: TActions) => {
      action.value = newAction
    }

    const onLogin = async () => {
      const result = await auth.login(loginData.username, loginData.password)
      return result
    }
    const onRegister = async () => {
      const result = await auth.register(registerData.name, registerData.surname, registerData.email, registerData.gender, registerData.password)
      return result
    }
    const buttonHandler = (type: TActions) => {
      if (type === action.value) {
        if (type === 'login') {
          return onLogin()
        }
        return onRegister()
      }
      if (type === 'login') {
        return setAction('login')
      }
      return setAction('register')
    }

    return {
      IUserResponceGender,
      auth,
      loginData,
      registerData,
      action,
      setAction,
      buttonHandler,
      onLogin,
      onRegister,
      isPasswordVisible,
    }
  },
})
</script>
