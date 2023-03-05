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
          <v-col cols="12" class="pt-4 pb-0">
            <v-text-field prepend-icon="mdi-email" label="Email" clearable></v-text-field>
          </v-col>
          <v-col cols="12" class="pt-1 pb-0">
            <v-text-field prepend-icon="mdi-eye-off" label="Password" clearable></v-text-field>
          </v-col>
          <v-col cols="12" class="text-center pt-1 pb-4">
            <v-btn :rounded="4">{{ $t('auth.login') }}</v-btn>
            <v-btn :rounded="4" variant="text">{{ $t('auth.register') }}</v-btn>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'Auth',
  setup() {},
})
</script>
