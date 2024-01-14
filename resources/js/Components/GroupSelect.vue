<template>
    <select
        @change="handleGroupChange"
        class="outline-none border border-solid border-gray-300 rounded-lg cursor-pointer p-1"
        :class="width"
    >
        <option v-for="(group, i) in groups" :value="group.id" :key="group.id">
            {{ group.icon }} {{ group.name }}
        </option>
    </select>
</template>

<script setup>
defineProps({
    groups: Object,
    width: { type: String, default: "w-full" },
});

const emit = defineEmits(["update:modelValue"]);

const handleGroupChange = (e) => {
    emit("update:modelValue", e.target.value);
};
</script>
