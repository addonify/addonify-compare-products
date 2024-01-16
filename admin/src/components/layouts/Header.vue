<script setup>
	import Logo from "../global/Logo.vue";
	import Icon from "../icons/Icons.vue";
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
				<div class="action-buttons">
					<div class="links">
						<a
							href="https://docs.addonify.com/kb/woocommerce-compare-products/"
							class="adfy-button fake-button has-underline"
							target="_blank"
						>
							<Icon icon="paper" />
							{{ __("ACP Docs", "addonify-compare-products") }}
						</a>
						<a
							href="https://wordpress.org/support/plugin/addonify-compare-products/reviews/?filter=5"
							class="adfy-button fake-button has-underline"
							target="_blank"
						>
							<Icon icon="sparkling-stars" />
							{{ __("Rate ACP", "addonify-compare-products") }}
						</a>
					</div>
					<div class="buttons">
						<button
							type="submit"
							@click="store.handleUpdateOptions()"
							class="adfy-button"
							:disabled="!store.needSaving"
							:loading="store.isSaving"
						>
							<span class="loading-icon">
								<Icon icon="spinner" />
							</span>
							{{
								__("Save Options", "addonify-compare-products")
							}}
						</button>
					</div>
				</div>
			</div>
		</div>
	</header>
</template>
