<template>
    <button
        class="bg-indigo-700 text-white p-3 rounded-lg duration-300 hover:bg-indigo-600"
        :class="classes"
        :type="type"
        @click="callback"
    >
        <slot />
    </button>
</template>

<script setup>
defineProps({
    classes: { type: String, default: "" },
    type: { type: String, default: "submit" },
    callback: { type: Function, default: null },
});
</script>
