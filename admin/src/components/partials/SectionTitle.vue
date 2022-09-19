<script setup>
	import { useOptionsStore } from "../../stores/options";

	const props = defineProps({
		section: Object,
		sectionkey: String,
		currentPage: String,
	});

	const store = useOptionsStore();

	//console.log(props.currentPage);

	const titleVisibility = (page) => {
		if (page === "design") {
			return true;
		} else if (page === "settings") {
			return props.sectionkey.includes("general")
				? true
				: store.options.enable_product_comparison;
		} else {
			return false;
		}
	};
</script>
<template>
	<h3
		class="option-box-title"
		v-if="props.section.title"
		v-show="titleVisibility(props.currentPage)"
	>
		{{ props.section.title }}
	</h3>
</template>
