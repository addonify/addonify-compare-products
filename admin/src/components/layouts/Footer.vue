<script setup>
	const { __ } = wp.i18n;
	const versionNumber = ADDONIFY_COMPARE_PRODUCTS_LOCALIZER.version_number;
	const currentYear = new Date().getFullYear();
</script>
<template>
	<footer class="adfy-colopon">
		<div class="adfy-row">
			<div class="adfy-col left">
				<p class="text">
					© 2020 - {{ currentYear }} Addonify Compare Products
					<span class="version">
						{{ __("Version", "addonify-compare-products") }}:
						{{ versionNumber }}
					</span>
				</p>
			</div>
			<div class="adfy-col right">
				<p class="text">
					<a
						href="https://wordpress.org/plugins/addonify-compare-products/#reviews"
						class="adfy-link"
						target="_blank"
					>
						{{ __("Rate", "addonify-compare-products") }}
						<span class="icon">
							<i class="dashicons dashicons-star-filled"></i>
							<i class="dashicons dashicons-star-filled"></i>
							<i class="dashicons dashicons-star-filled"></i>
							<i class="dashicons dashicons-star-filled"></i>
							<i class="dashicons dashicons-star-filled"></i>
						</span>
						:)
					</a>
				</p>
			</div>
		</div>
	</footer>
</template>
