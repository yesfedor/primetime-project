<template>
  <component :is="layout" :error="error"></component>
</template>

<script lang="ts">
import { useRoute } from 'vue-router'
import { defineComponent, watch, ref } from 'vue'
import AppLayoutDefault from '@/layouts/default.vue'
import AppLayoutError from '@/layouts/error.vue'

export default defineComponent({
  name: 'AppCoreLayoutProvider',
  setup() {
    const $route = useRoute()
    const layoutDefault = Object.freeze(AppLayoutDefault)
    const layoutError = Object.freeze(AppLayoutError)
    let layout = ref<typeof AppLayoutError | typeof AppLayoutDefault>(AppLayoutDefault)
    let error = ref<unknown>(undefined)
    const hook = async () => {
      try {
        const component = await import(`@/layouts/${$route.meta.layout}.vue`)
        if (component?.default) {
          layout = Object.freeze(component)
        } else {
          layout.value = layoutDefault
        }
      } catch (e: unknown) {
        error.value = e
        layout.value = layoutError
      }
    }
    watch($route, hook, { immediate: true })

    return {
      layout,
      error,
    }
  },
})
</script>
