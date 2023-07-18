<template>
	<v-container>
		<v-row>
			<v-col cols="12">
				<h1>Админ зона</h1>
			</v-col>
			<v-col>
				<v-divider />
			</v-col>
		</v-row>
		<v-row>
			<v-col cols="12" md="6">
				<v-card>
					<v-card-title>Просмотренное пользователями</v-card-title>
					<v-card-actions>
						<v-btn :to="{ name: RouteNamesEnum.adminViewed }">Открыть</v-btn>
					</v-card-actions>
				</v-card>
			</v-col>
		</v-row>
	</v-container>
</template>

<script lang="ts" setup>
import { RouteNamesEnum } from '@/router/router.types'
</script>
