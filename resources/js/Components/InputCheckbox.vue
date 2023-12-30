<template>
    <label class="flex gap-2 items-center cursor-pointer">
        <input
            type="checkbox"
            class="h-4 w-4 cursor-pointer"
            :required="required"
            :checked="checked"
            @change="handleCheckboxChange"
            ref="checkbox"
        />
        <p>
            <slot />
        </p>
    </label>
</template>

<script setup>
import { ref } from "vue";

defineProps({
    required: { type: Boolean, default: false },
    checked: { type: Boolean, default: false },
});

const checkbox = ref(null);

const emit = defineEmits(["update:modelValue"]);

const handleCheckboxChange = () => {
    emit("update:modelValue", checkbox.value.checked);
};
</script>
