<script setup>
	import Logo from "../core/Logo.vue";
	import Icon from "../core/Icon.vue";
	import { useOptionsStore } from "../../stores/options";

	const { __ } = wp.i18n;
	const store = useOptionsStore();
</script>

<template>
	<header class="adfy-header">
		<div class="adfy-row">
			<div class="adfy-col start">
				<div class="branding">
					<router-link class="adfy-link" to="/">
						<Logo />
					</router-link>
				</div>
			</div>
			<div class="adfy-col end">
				<div class="buttons">
					<a
						href="https://docs.addonify.com/kb/woocommerce-compare-products/"
						class="adfy-button fake-button has-underline"
						target="_blank"
					>
						{{ __("Documentation", "addonify-compare-products") }}
					</a>
					<button
						type="submit"
						@click="store.handleUpdateOptions()"
						class="adfy-button"
						:disabled="!store.needSave"
						:loading="store.isSaving"
					>
						<span class="loading-icon">
							<Icon name="spinner" />
						</span>
						{{ __("Save Options", "addonify-compare-products") }}
					</button>
				</div>
			</div>
		</div>
	</header>
</template>
