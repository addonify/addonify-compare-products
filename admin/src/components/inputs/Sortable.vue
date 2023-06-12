<script setup>
	import { computed, watch } from "vue";
	import { useOptionsStore } from "../../stores/options";
	import draggable from "vuedraggable";
	import { ElSwitch } from "element-plus";
	import { Check, Close } from "@element-plus/icons-vue";

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
</script>
<template>
	<div class="adfy-draggable-elements">
		<draggable
			v-model="value"
			@start="drag = true"
			@end="drag = false"
			item-key="id"
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
		<!--<Sortable
			:list="props.elements"
			item-key="id"
			tag="div"
			@update="handleUpdate"
			@change="handleChange"
			:options="options"
		>
			<template #item="{ element, index }">
				<div class="adfy-draggable-element" :key="element.id">
					<div class="adfy-draggable-box">
						<div class="draggable-switch">
							<el-switch
								v-model="valll"
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
		</Sortable>-->
	</div>
</template>
