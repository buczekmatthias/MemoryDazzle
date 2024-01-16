<template>
    <button
        :class="
            twMerge(
                'bg-indigo-700 text-white p-3 rounded-lg duration-300 hover:bg-indigo-600',
                classes
            )
        "
        :type="type"
        :disabled="disabled"
    >
        <slot />
    </button>
</template>

<script setup>
import { twMerge } from "tailwind-merge";

defineProps({
    classes: { type: String, default: "" },
    type: { type: String, default: "submit" },
    disabled: Boolean,
});
</script>
