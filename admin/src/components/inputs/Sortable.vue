<script setup>
	import { computed } from "vue";
	import draggable from "vuedraggable";
	import { ElSwitch } from "element-plus";
	import { Check, Close } from "@element-plus/icons-vue";
	import Notice from "../layouts/Notice.vue";

	/**
	 *
	 * Define props.
	 * Props are reactive.
	 *
	 * @since: 1.1.9
	 */
	const props = defineProps({
		modelValue: {
			type: [String, Array, Object], // Loose type checking.
			required: true,
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
			//console.log(typeof props.modelValue);
			//console.log(props.modelValue);
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

	/**
	 *
	 * Return current timestamp.
	 *
	 * @param {void} void
	 * @return {number} timestamp
	 * @since 1.1.9
	 */

	const currentTime = () => new Date().getTime();

	console.log(value);
</script>
<template>
	<template v-if="value.length !== 0">
		<div class="adfy-draggable-elements" :v-node="currentTime()">
			<draggable
				v-model="value"
				animation="400"
				easing="ease-in-out"
				item-key="id"
				direction="vertical"
				@start="drag = true"
				@end="drag = false"
			>
				<template #item="{ element }">
					<div class="adfy-draggable-element" :key="element.id">
						<div class="adfy-draggable-box">
							<div class="draggable-switch">
								<el-switch
									v-model="element.status"
									size="large"
									inline-prompt
									:active-icon="Check"
									:inactive-icon="Close"
								/>
							</div>
							<div class="label-icon-box">
								<p class="option-label">{{ element.name }}</p>
								<span class="option-icon">
									<svg
										xmlns="http://www.w3.org/2000/svg"
										fill="currentColor"
										viewBox="0 0 24 24"
									>
										<path
											d="M7 20h2V8h3L8 4 4 8h3zm13-4h-3V4h-2v12h-3l4 4z"
										/>
									</svg>
								</span>
							</div>
						</div>
					</div>
				</template>
			</draggable>
		</div>
	</template>
	<template v-else>
		<Notice :content="fallbackText" type="info" />
	</template>
</template>
