<template>
  <v-row v-if="isShow" class="mt-6">
    <v-col cols="12">
      <h4 class="text-h6">Рецензии</h4>
    </v-col>
    <v-col>
      <v-expansion-panels class="w-100">
        <template
          v-for="(panels, key) of items"
          :key="key"
        >
          <template
            v-for="panel in panels"
            :key="panel.reviewDescription"
          >
            <v-expansion-panel
              :class="{
                'd-none': key === 'UNKNOWN'
              }"
            >
              <v-expansion-panel-title class="pa-3">
                <div class="d-flex flex-column pe-3 w-75">
                  <span class="text-truncate text-body-1">
                    {{ panel?.reviewTitle || `${ panel.reviewDescription.slice(0, 32) }...` }}&nbsp;
                  </span>
                  <span :class="classByKey[panel.reviewType]" class="text-start text-caption">
                    {{ titleByKey[panel.reviewType] }}
                  </span>
                </div>
              </v-expansion-panel-title>
              <v-expansion-panel-text class="text-body-2">
                {{ panel.reviewDescription }}
              </v-expansion-panel-text>
            </v-expansion-panel>
          </template>
        </template>
      </v-expansion-panels>
    </v-col>
  </v-row>
</template>

<script lang="ts" setup>
import { WatchApiGetReviews } from '~/api/watch'
import {computed, toRefs} from 'vue'

interface Props {
  items: WatchApiGetReviews['items']
}

const props = defineProps<Props>()
const { items } = toRefs(props)

const titleByKey = {
  NEGATIVE: 'Негативный',
  NEUTRAL: 'Нейтральный',
  POSITIVE: 'Положительный',
}

const classByKey = {
  NEGATIVE: 'text-red-darken-2',
  NEUTRAL: 'Нейтральный',
  POSITIVE: 'text-green-darken-2',
}

const isShow = computed(() => {
  return items.value?.NEGATIVE.length || items.value?.NEUTRAL.length || items.value?.POSITIVE.length
})
</script>
