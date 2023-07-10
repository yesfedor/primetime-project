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
                prepend-inner-icon="mdi-email"
                variant="outlined"
                clearable
              />
            </v-col>
            <v-col cols="12" class="pt-1 pb-0">
              <v-text-field
                v-model="loginData.password"
                :label="$t('auth.password')"
                :prepend-inner-icon="isPasswordVisible ? 'mdi-eye' : 'mdi-eye-off'"
                :type="isPasswordVisible ? 'text' : 'password'"
                variant="outlined"
                clearable
                @click:prepend-inner="isPasswordVisible = !isPasswordVisible"
              />
            </v-col>
          </template>
          <template v-if="action === 'register'">
            <v-col cols="12" class="pt-4 pb-0">
              <v-text-field
                v-model="registerData.name"
                :label="$t('auth.name')"
                variant="outlined"
              />
            </v-col>
            <v-col cols="12" class="pt-1 pb-0">
              <v-text-field
                v-model="registerData.surname"
                :label="$t('auth.surname')"
                variant="outlined"
              />
            </v-col>
            <v-col cols="12" class="pt-1 pb-0">
              <v-text-field
                v-model="registerData.email"
                :label="$t('auth.email')"
                prepend-inner-icon="mdi-email"
                variant="outlined"
                clearable
              />
            </v-col>
            <v-col cols="12" class="pt-1 pb-0">
              <v-text-field
                v-model="registerData.password"
                :label="$t('auth.password')"
                :prepend-inner-icon="isPasswordVisible ? 'mdi-eye' : 'mdi-eye-off'"
                :type="isPasswordVisible ? 'text' : 'password'"
                variant="outlined"
                clearable
                @click:prepend-inner="isPasswordVisible = !isPasswordVisible"
              />
            </v-col>
            <v-col cols="12" class="pt-1 pb-0">
              <v-radio-group inline>
                <v-radio :label="$t('auth.male')" :value="IUserResponceGender.male" />
                <v-radio :label="$t('auth.female')" :value="IUserResponceGender.female" />
              </v-radio-group>
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
import { RouteNamesEnum } from '@/router/router.types'
import { useRouter, useRoute } from 'vue-router'
import { defineComponent, ref, reactive } from 'vue'
import { useToast } from 'vue-toastification'
import { useI18n } from 'vue-i18n'
import { UTM_SOURCE_KEY, UTM_SOURCE } from '@/const/utm'

type TActions = 'login' | 'register'

export default defineComponent({
  name: 'Auth',
  setup() {
    const router = useRouter()
    const route = useRoute()
    const auth = useAuth()
    const isPasswordVisible = ref(false)
    const toastr = useToast()
    const { t } = useI18n()
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

    const onSuccessRouterPush = () => {
      if (typeof route.query?.from === 'string' && route.query.from.startsWith('/')) {
        return router.push({
          path: route.query.from,
          query: {
            [UTM_SOURCE_KEY]: UTM_SOURCE.authfrom,
          },
        })
      }
      return router.push({
        name: RouteNamesEnum.home,
        query: {
          [UTM_SOURCE_KEY]: UTM_SOURCE.authpage,
        },
      })
    }

    const onLogin = async () => {
      const result = await auth.login(loginData.username, loginData.password)
      if (result.status === 1) {
        onSuccessRouterPush()
        return result
      }

      toastr.info(t(`auth.errors.${result.status}`))
    }

    const onRegister = async () => {
      if ([registerData.name, registerData.surname, registerData.email, registerData.gender, registerData.password].find((item) => !item)) {
        return false
      }

      const result = await auth.register(registerData.name, registerData.surname, registerData.email, registerData.gender, registerData.password)

      if (result.status === 1) {
        onSuccessRouterPush()
        return result
      }

      toastr.info(t(`auth.errors.${result.status}`))
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
