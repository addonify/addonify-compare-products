<script setup>
	import Icon from "../icons/Icons.vue";
	const { __ } = wp.i18n;
</script>
<template>
	<nav class="adfy-navigation">
		<ul class="navigation">
			<li>
				<router-link to="/">
					<span class="icon">
						<Icon icon="gears" />
					</span>
					{{ __("Settings", "addonify-compare-products") }}
				</router-link>
			</li>
			<li>
				<router-link to="/styles">
					<span class="icon">
						<Icon icon="paint" />
					</span>
					{{ __("Design", "addonify-compare-products") }}
				</router-link>
			</li>
			<li>
				<router-link to="/products">
					<span class="icon">
						<Icon icon="trophy" />
					</span>
					{{ __("Products", "addonify-compare-products") }}
				</router-link>
			</li>
		</ul>
	</nav>
</template>
