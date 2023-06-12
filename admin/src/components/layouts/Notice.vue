<script setup>
	/**
	 *
	 * Define props.
	 * Props are reactive.
	 *
	 * @since: 1.1.9
	 */
	const props = defineProps({
		type: {
			type: String,
			required: false,
			default: "info",
		},
		content: {
			type: String,
			required: true,
		},
	});

	const { type, content } = props;
</script>

<template>
	<div class="adfy_alert" :class="type">
		<div class="icon">
			<template v-if="type == 'success'">
				<svg fill="currentColor" viewBox="0 0 16 16">
					<path
						d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"
					/>
				</svg>
			</template>
			<template v-else-if="type == 'info'">
				<svg fill="currentColor" viewBox="0 0 16 16">
					<path
						d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"
					/>
				</svg>
			</template>
			<template v-else-if="type == 'warning'">
				<svg fill="currentColor" viewBox="0 0 16 16">
					<path
						d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"
					/>
				</svg>
			</template>
			<template v-else-if="type == 'danger'">
				<svg fill="currentColor" viewBox="0 0 16 16">
					<path
						d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"
					/>
				</svg>
			</template>
			<template v-else>
				<svg fill="currentColor" viewBox="0 0 16 16">
					<path
						d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"
					/>
				</svg>
			</template>
		</div>
		<div class="message">
			<p class="content">{{ content }}</p>
		</div>
	</div>
</template>
