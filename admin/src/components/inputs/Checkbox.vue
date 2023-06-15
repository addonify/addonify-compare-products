<script setup>
	import { computed } from "vue";
	import Notice from "../layouts/Notice.vue";

	/**
	 *
	 * Define props.
	 * Props are reactive.
	 *
	 * @since: 1.0.0
	 */
	const props = defineProps({
		modelValue: {
			type: [String, Array, Object], // Loose type checking.
			required: true,
		},
		label: {
			type: String,
			required: false,
		},
		choices: {
			type: Object,
			required: false,
		},
		fallbackText: {
			type: String,
			required: false,
		},
	});

	/**
	 *
	 * Define emits for v-model usage.
	 * Ref: https://vuejs.org/guide/components/events.html#usage-with-v-model
	 *
	 */
	const emit = defineEmits(["update:modelValue"]);
	const value = computed({
		get() {
			return props.modelValue;
		},
		set(newValue) {
			emit("update:modelValue", newValue);
		},
	});

	/**
	 *
	 * Import i18n.
	 *
	 */

	const { __ } = wp.i18n;

	/**
	 *
	 * Fall back text for fallbackText prop.
	 *
	 */

	let fallbackText = props.fallbackText
		? props.fallbackText
		: __("No choices found.", "addonify-compare-products");

	//console.log(props.choices);
</script>
<template>
	<template v-if="Object.keys(props.choices).length !== 0">
		<div class="adfy-checkbox-group">
			<span v-for="(name, key) in props.choices" class="input-checkbox">
				<input type="checkbox" :id="key" :value="key" v-model="value" />
				<label :for="key"> {{ name }}</label>
			</span>
		</div>
	</template>
	<template v-else>
		<Notice type="info" :content="fallbackText" />
	</template>
</template>
<style lang="scss">
	.adfy-checkbox-group {
		display: flex;
		flex-wrap: wrap;
		flex-direction: row;
		gap: 20px;
		justify-content: flex-start;
		align-items: center;
		.input-checkbox {
			display: inline-flex;
			flex-wrap: wrap;
			flex-direction: row;
			align-items: center;

			input[type="checkbox"] {
				position: relative;
				display: inline-block !important;
				border: 2px solid var(--addonify_border_color);
				border-radius: 2px !important;
				background: none;
				clear: none;
				cursor: pointer;
				line-height: 0 !important;
				margin: 0 3px 0 0 !important;
				outline: 0 !important;
				padding: 0 !important;
				text-align: center;
				vertical-align: text-top;
				height: 21px !important;
				width: 21px !important;
				min-width: 21px !important;
				opacity: 1 !important;
				box-shadow: none !important;
				transition: all 0.5s ease;
				-webkit-appearance: none;
				-moz-appearance: none;

				&:hover {
					border-color: var(--addonify_primary_color);
					opacity: 1;
					background-color: transparent;
					box-shadow: none;
				}

				&:focus {
					box-shadow: none;
				}

				&:before {
					content: "";
					display: none;
				}

				&:after {
					content: "";
					position: absolute !important;
					z-index: 2 !important;
					left: 0px !important;
					top: 0px !important;
					width: 18px !important;
					height: 18px !important;
					border: none !important;
					opacity: 0 !important;
					overflow: hidden !important;
					margin: 0 !important;
					padding: 0 !important;
					border-radius: 0 !important;
					background-color: transparent !important;
					background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='24' height='24'%3E%3Cpath fill='none' d='M0 0h24v24H0z'/%3E%3Cpath d='M10 15.172l9.192-9.193 1.415 1.414L10 18l-6.364-6.364 1.414-1.414z' fill='rgba(255,255,255,1)'/%3E%3C/svg%3E") !important;
					background-repeat: no-repeat;
					background-size: 18px;
					background-position: center;
					transition: all 0.26s cubic-bezier(0.25, 0.8, 0.25, 1);
				}

				&:checked {
					border-color: var(--addonify_primary_color);
					background-color: var(--addonify_primary_color);
					opacity: 1 !important;
					box-shadow: none !important;

					&:before {
						content: "";
						display: none !important;
					}

					&:after {
						content: "";
						opacity: 1 !important;
					}
				}
			}
			label {
				margin-left: 3px;
			}
		}
	}
</style>
