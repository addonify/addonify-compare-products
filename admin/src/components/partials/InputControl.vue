<script setup>
	import { reactive } from "vue";
	import Switch from "../inputs/Switch.vue";
	import Text from "../inputs/Text.vue";
	import Textarea from "../inputs/Textarea.vue";
	import Number from "../inputs/Number.vue";
	import Select from "../inputs/Select.vue";
	import Checkbox from "../inputs/Checkbox.vue";
	import CheckboxButton from "../inputs/CheckboxButton.vue";
	import Radio from "../inputs/Radio.vue";
	import ColorPicker from "../inputs/ColorPicker.vue";
	import InvalidControl from "../inputs/InvalidControl.vue";
	import NumberToggleButton from "../inputs/NumberToggleButton.vue";
	import Sortable from "../inputs/Sortable.vue";
	const props = defineProps({
		field: Object,
		fieldKey: String,
		label: String,
		reactiveState: Object,
	});

	//console.log(props.field);
</script>
<template>
	<Switch
		v-if="props.field.type == 'switch'"
		v-model="props.reactiveState[props.fieldKey]"
	/>
	<Select
		v-else-if="props.field.type == 'select'"
		v-model="props.reactiveState[props.fieldKey]"
		:choices="props.field.choices"
		:placeholder="props.field.placeholder"
	/>
	<Text
		v-else-if="props.field.type == 'text'"
		v-model="props.reactiveState[props.fieldKey]"
		:placeholder="props.field.placeholder"
	/>
	<Textarea
		v-else-if="props.field.type == 'textarea'"
		v-model="props.reactiveState[props.fieldKey]"
		:placeholder="props.field.placeholder"
	/>
	<CheckboxButton
		v-else-if="
			props.field.type == 'checkbox' && props.field.typeStyle == 'buttons'
		"
		v-model="props.reactiveState[props.fieldKey]"
		:choices="props.field.choices"
	/>
	<Checkbox
		v-else-if="props.field.type == 'checkbox'"
		v-model="props.reactiveState[props.fieldKey]"
		:choices="props.field.choices"
		:fallbackText="props.field.fallback_text"
	/>
	<NumberToggleButton
		v-else-if="
			props.field.type == 'number' && props.field.typeStyle == 'toggle'
		"
		v-model="props.reactiveState[props.fieldKey]"
		:placeholder="props.field.placeholder"
	/>
	<Number
		v-else-if="props.field.type == 'number'"
		v-model="props.reactiveState[props.fieldKey]"
		:placeholder="props.field.placeholder"
	/>
	<Radio
		v-else-if="props.field.type == 'radio'"
		v-model="props.reactiveState[props.fieldKey]"
		:choices="props.field.choices"
		:renderChoices="props.field.renderChoices"
	/>
	<ColorPicker
		v-else-if="props.field.type == 'color'"
		v-model:colorVal="props.reactiveState[props.fieldKey]"
		:isAlphaPicker="props.field.isAlphaPicker"
		:label="props.field.label"
	/>
	<Sortable
		v-else-if="props.field.type == 'sortable'"
		:fallbackText="props.field.fallback_text"
		v-model="props.reactiveState[props.fieldKey]"
	/>
	<InvalidControl v-else />
</template>
