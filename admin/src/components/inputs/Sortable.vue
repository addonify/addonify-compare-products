<script setup>
	import { computed, watch } from "vue";
	import { useOptionsStore } from "../../stores/options";
	import draggable from "vuedraggable";
	//import { Sortable } from "sortablejs-vue3";
	import { ElSwitch } from "element-plus";
	import { Check, Close } from "@element-plus/icons-vue";
	const store = useOptionsStore();

	const props = defineProps({
		elements: {
			type: [Object, Array, String],
			required: true,
		},
	});

	/**
	 *
	 * Emit the model value by using get & set methods.
	 * Ref: https://vuejs.org/guide/components/events.html#usage-with-v-model
	 */

	const emit = defineEmits(["update:elements"]);
	const value = computed({
		get() {
			return props.elements;
		},
		set(newValue) {
			emit("update:elements", newValue);
		},
	});

	const options = {
		ghostClass: "sortable-ghost", // Class name for the drop placeholder.
		chosenClass: "sortable-chosen", // Class name for the chosen item.
		dragClass: "sortable-drag", // Class name for the dragging item.
		animation: 300,
		group: {
			name: "shared",
			pull: "clone",
		},
	};

	watch(
		() => store.sortable,
		(newValue, oldValue) => {
			console.log(newValue);
			console.log(oldValue);
		},
		{ deep: true }
	);
</script>
<template>
	<div class="adfy-draggable-elements">
		<draggable
			v-model="store.sortable"
			@start="drag = true"
			@end="drag = false"
			item-key="id"
			@change="handleChange"
		>
			<template #item="{ element }">
				<div class="adfy-draggable-element" :key="element.id">
					<div class="adfy-draggable-box">
						<div class="draggable-switch">
							<el-switch
								v-model="element.enabled"
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
