<script setup>
	import { useOptionsStore } from "../../stores/options";
	import InputControl from "./InputControl.vue";
	import { ElTag } from "element-plus";
	const props = defineProps({
		section: Object,
		sectionKey: [String, Object],
		reactiveState: Object,
	});
	const store = useOptionsStore();
</script>
<template>
	<slot></slot>
	<div
		class="adfy-options"
		v-for="(field, key) in props.section.fields"
		v-show="
			key == 'enable_product_comparision'
				? true
				: store.options.enable_product_comparision
		"
	>
		<div class="adfy-option-columns option-box" :class="field.className">
			<div class="adfy-col left">
				<div class="label">
					<p v-if="field.label" class="option-label">
						{{ field.label }}
						<el-tag
							v-if="field.hasOwnProperty('badge')"
							:type="field.badgeType ? field.badgeType : ''"
						>
							{{ field.badge }}
						</el-tag>
					</p>
					<p v-if="field.description" class="option-description">
						{{ field.description }}
					</p>
				</div>
			</div>
			<div class="adfy-col right">
				<div class="input">
					<InputControl
						:field="field"
						:fieldKey="key"
						:reactiveState="props.reactiveState"
					/>
				</div>
			</div>
		</div>
	</div>
	<!-- // adfy-options -->
</template>
