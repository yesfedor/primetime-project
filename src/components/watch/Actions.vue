<template>
	<v-menu location="left center">
		<template #activator="{ props }">
			<v-btn variant="text" icon="mdi-dots-vertical" v-bind="props" @click.prevent></v-btn>
		</template>
		<v-list width="320" bg-color="secondary" class="me-lg-1">
			<v-list-item>
				<v-btn variant="text" icon="fa:fab fa-vk" />
				<v-btn variant="text" icon="fa:fab fa-telegram" />
				<v-btn variant="text" icon="fa:fab fa-twitter" />
				<v-btn variant="text" icon="fa:fas fa-share" />
				{{ kinopoiskId }}
			</v-list-item>
		</v-list>
	</v-menu>
</template>

<script lang="ts" setup>
import { defineProps, toRefs } from 'vue'

interface Props {
	kinopoiskId: number
}

const props = defineProps<Props>()
const { kinopoiskId } = toRefs(props)
</script>
