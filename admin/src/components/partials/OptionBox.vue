<script setup>
	import { computed } from "vue";
	import { useOptionsStore } from "../../stores/options";
	import InputControl from "./InputControl.vue";
	import { ElTag } from "element-plus";

	const props = defineProps({
		section: Object,
		sectionKey: [String, Object],
		reactiveState: Object,
	});

	const store = useOptionsStore();

	const mainOption = "enable_product_comparison";

	function optionDependencyHandler(args) {
		//console.log(args);
		if (Array.isArray(args)) {
			return args.every((key) => {
				return store.options[key];
			});
		} else {
			return store.options[args];
		}
	}

	function optionVisibilityHandler(key, dependent) {
		return key == mainOption ? true : optionDependencyHandler(dependent);
	}

	//function print(arg) {
	//	console.log(arg);
	//}
</script>
<template>
	<slot></slot>
	<div
		class="adfy-options"
		v-for="(field, key) in props.section.fields"
		v-show="optionVisibilityHandler(key, field.dependent)"
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
