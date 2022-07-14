<script setup>
	import { useOptionsStore } from "../../stores/options";
	let props = defineProps({
		section: Object,
		sectionkey: String,
	});
	let store = useOptionsStore();
	console.log(props.sectionkey);
</script>
<template>
	<h3
		v-if="props.section.title"
		class="option-box-title"
		v-show="
			props.sectionkey == 'general'
				? true
				: store.options.enable_product_comparision
		"
	>
		{{ props.section.title }}
	</h3>
</template>
