<script setup>
	import { computed } from "vue";
	import { ElSelect, ElOption } from "element-plus";

	const props = defineProps({
		modelValue: {
			type: [Number, String, Array, Object],
			required: true,
		},
		choices: {
			type: [Object, Array],
			required: false,
		},
		placeholder: {
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
			return props.modelValue.toString();
		},
		set(newValue) {
			emit("update:modelValue", newValue);
		},
	});

	const { __ } = wp.i18n;
	const defPlaceholder = __("Select", "addonify-compare-products");
</script>
<template>
	<el-select
		v-model="value"
		size="large"
		filterable
		:placeholder="props.placeholder ? props.placeholder : defPlaceholder"
	>
		<el-option
			v-for="(label, key) in props.choices"
			:label="label"
			:value="key"
		/>
	</el-select>
</template>
<style lang="css">
	.wp-admin .el-select-dropdown__item.selected {
		font-weight: normal;
	}
</style>
