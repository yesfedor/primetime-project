<template>
  <div class="app-trand">
    <AppWatchParallax
      :poster-url="firstTrandItem && firstTrandItem.posterUrl || ''"
      label-key="trands.title"
      :loading="trandsIsLoading"
    />
    <v-container>
      <v-tabs :model-value="selectedTab" @update:model-value="onChangeTab">
        <v-tab v-for="item in WATCH_TRANDS_TABS" :key="item.act" :value="item.act" density="compact">
          <span>{{ $t(`trands.filters.${item.labelKey}`) }}</span>
        </v-tab>
      </v-tabs>
      <v-window :model-value="selectedTab" class="mt-8">
        <v-window-item v-if="!trandList.length" :value="selectedTab">
          <v-row>
            <v-col class="text-center">
              <h2>{{ trandsIsLoading ? $t('app.loading') : $t('app.no_result') }}</h2>
              <v-progress-circular indeterminate size="32" width="4" class="mt-5" />
            </v-col>
          </v-row>
        </v-window-item>
        <v-window-item :value="selectedTab">
          <AppWatchList :list="trandList" />
        </v-window-item>
      </v-window>
    </v-container>
  </div>
</template>

<script lang="ts" setup>
import { onMounted, ref, computed } from 'vue'
import { WatchApiContentItem, watchApi } from '@/api/watch'
import { WATCH_TRANDS_TABS } from '@/const/watch'
import AppWatchList from '@/components/watch/List.vue'
import AppWatchParallax from '@/components/watch/Parallax.vue'

const trandsIsLoading = ref(false)
const trandList = ref<WatchApiContentItem[]>([])
const selectedTab = ref('')

const firstTrandItem = computed(() => {
  if (!trandList.value.length) {
    return
  }
  return trandList.value[0]
})

const loadingTrands = async (act = '') => {
  trandList.value = []
  trandsIsLoading.value = true
  const result = await watchApi.getTrands(act)
  if (result?.total) {
    trandList.value = result.content
  }
  trandsIsLoading.value = false
}

const onChangeTab = (value: any) => {
  selectedTab.value = value
  loadingTrands(selectedTab.value)
}

onMounted(() => loadingTrands())
</script>
