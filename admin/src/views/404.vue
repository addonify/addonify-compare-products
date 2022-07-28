<script setup>
	const { __ } = wp.i18n;
</script>
<template>
	<div class="error-404">
		<h3>{{ __("404", "addonify-compare-products") }}</h3>
		<p>{{ __("Oops, page not found!", "addonify-compare-products") }}</p>
		<router-link to="/" class="adfy-button">
			{{ __("Go Back", "addonify-compare-products") }}
		</router-link>
	</div>
</template>
