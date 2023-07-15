<template>
	<v-dialog
		v-model="dialog"
		width="auto"
		close-on-content-click
	>
		<template v-slot:activator="{ props }">
			<v-btn
				variant="flat"
				color="primary"
				v-bind="props"
			>
				{{ $t('player.select') }}
			</v-btn>
		</template>
		<v-list class="pa-8">
			<v-list-item>
				<v-list-item-title>{{ $t('player.select') }}</v-list-item-title>
			</v-list-item>
			<v-list-item
				v-for="alias in PlayerAlias"
				:key="alias"
				:active="playerAlias === alias"
				link
				@click="setPlayerAlias(alias)"
			>
				{{ alias }}
			</v-list-item>
		</v-list>
	</v-dialog>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { usePlayer, PlayerAlias } from '@/composables/usePlayer'

const dialog = ref(false)
const { playerAlias, setPlayerAlias } = usePlayer(null)
</script>
