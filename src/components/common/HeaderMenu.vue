<template>
  <AppSearchTrigger />
  <AppAuthTrigger />  
  <AppNavigationSettings />
</template>

<script lang="ts" setup>
import AppSearchTrigger from '@/components/search/Trigger.vue'
import AppNavigationSettings from '@/components/navigation/Settings.vue'
import AppAuthTrigger from '@/components/auth/Trigger.vue'
</script>
