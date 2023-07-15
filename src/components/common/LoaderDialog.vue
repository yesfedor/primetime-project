<template>
	<v-dialog
		v-model="dialog"
		:scrim="false"
		width="100%"
		fullscreen
		absolute
		transition="fade-transition"
		content-class="app-loader-dialog"
	>
		<v-row class="d-flex align-center justify-center app-loader-dialog__wrapper ma-0">
			<v-col cols="12" class="app-logo text-center">
				<span class="app-loader-dialog__logo text-h3 text-sm-h2 text-lg-h1">PRIMETIME</span>
			</v-col>
		</v-row>
	</v-dialog>
</template>

<script lang="ts" setup>
import { ref } from 'vue'

const dialog = ref(false)
setTimeout(() => {
	dialog.value = true
}, 1500)
</script>

<style lang="scss">
.app-loader-dialog {
	& &__logo {
		letter-spacing: 4px !important;
		font-weight: map-get($font-weights, 'light');
		font-family: $font-montserrat-alt !important;
		background-clip: text !important;
		-webkit-text-fill-color: transparent !important;

		@media #{map-get($display-breakpoints, "md-and-up")} {
			letter-spacing: 16px !important;
		}

		@media #{map-get($display-breakpoints, "lg-and-up")} {
			letter-spacing: 24px !important;
		}
	}
}

.v-dialog.v-theme--lightTheme {
	.app-loader-dialog {
		&__wrapper {
			background: $color-white;
		}
		&__logo {
			background: $logo-gradient-light;
		}
	}
}
.v-dialog.v-theme--darkTheme {
	.app-loader-dialog {
		&__wrapper {
			background: $color-black;
		}
		&__logo {
			background: $logo-gradient-dark;
		}
	}
}
</style>
