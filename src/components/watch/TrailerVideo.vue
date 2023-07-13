<template>
  <v-responsive :aspect-ratio="16 / 9" class="watch-trailer-video">
    <iframe
      :title="title"
      width="100%"
      :src="embededTrailerUrl"
      allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
      allowfullscreen
      class="watch-trailer-video__content"
    />
  </v-responsive>
</template>

<script lang="ts" setup>
import { toRefs, defineProps, computed } from 'vue'

interface Props {
  trailerUrl: string
  title: string
}

const linkRexep = /(vi\/|v%3D|v=|\/v\/|youtu\.be\/|\/embed\/)/

const props = defineProps<Props>()
const { trailerUrl, title } = toRefs(props)

const embededTrailerUrl = computed(() => {
  const trailerLink = trailerUrl.value.split(linkRexep)
  if (trailerLink[2]) {
    return `https://www.youtube.com/embed/${trailerLink[2]}`
  }
  return ''
})
</script>

<style lang="scss">
.watch-trailer-video {
  & &__content {
    height: 280px;
  }
}
</style>
