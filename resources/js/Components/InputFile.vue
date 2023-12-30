<template>
    <label
        :class="
            twMerge(
                'flex flex-col items-center justify-center border-4 border-dashed border-gray-400 text-gray-400 !bg-opacity-10 p-6 cursor-pointer duration-300',
                errors.length > 0
                    ? 'border-red-600 bg-red-600 text-red-600'
                    : '',
                hasImage
                    ? 'border-emerald-600 bg-emerald-600 text-emerald-600'
                    : '',
                'hover:border-indigo-700 hover:text-indigo-700 hover:bg-indigo-700'
            )
        "
    >
        <input
            type="file"
            class="hidden"
            @change="handleFileChange"
            :required="required"
            :accept="accepted"
        />
        <span class="text-xl font-semibold">{{ label }}</span>
        <span v-if="required === false">(Optional)</span>
        <span
            class="text-red-600 text-lg mt-2"
            v-if="errors.length > 0 && !hasImage"
            >{{ errors[0] }}</span
        >
        <span v-if="hasImage" class="text-lg mt-2">File selected</span>
    </label>
</template>

<script setup>
import { ref } from "vue";
import { twMerge } from "tailwind-merge";

defineProps({
    label: String,
    required: { type: Boolean, default: false },
    errors: { type: Array, default: [] },
    accepted: String,
});

const hasImage = ref(false);

const emit = defineEmits(["update:modelValue"]);

const handleFileChange = (e) => {
    hasImage.value = e.target.files.length > 0 ? true : false;
    emit("update:modelValue", e.target.files[0]);
};
</script>
