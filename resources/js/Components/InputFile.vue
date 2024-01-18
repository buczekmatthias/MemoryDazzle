<template>
    <label
        :class="
            twMerge(
                'flex flex-col items-center justify-center rounded-lg border-4 border-dashed border-gray-400 text-gray-400 !bg-opacity-10 p-6 cursor-pointer duration-300',
                errors.length > 0
                    ? 'border-red-600 bg-red-600 text-red-600'
                    : '',
                hasFile
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
            :multiple="limit > 1"
            ref="inp"
        />
        <span class="text-xl font-semibold">{{ label }}</span>
        <span v-if="required === false">(Optional)</span>
        <span
            class="text-red-600 text-lg mt-2 text-center"
            v-if="errors.length > 0 && !hasFile"
            >{{ errors[0] }}</span
        >
        <span v-if="hasFile" class="text-lg mt-2"
            >{{
                $refs.inp.files.length > 1
                    ? `${$refs.inp.files.length} files`
                    : "File"
            }}
            selected</span
        >
    </label>
</template>

<script setup>
import { ref } from "vue";
import { twMerge } from "tailwind-merge";

const props = defineProps({
    label: String,
    required: { type: Boolean, default: false },
    errors: { type: Array, default: [] },
    accepted: String,
    limit: {
        type: Number,
        default: 1,
    },
    hasFile: { type: Boolean, default: false },
});

const inp = ref(null);

const emit = defineEmits(["update:modelValue"]);

const handleFileChange = (e) => {
    if (inp.value.files.length <= props.limit) {
        emit(
            "update:modelValue",
            props.limit === 1 ? e.target.files[0] : e.target.files
        );
    }
};
</script>
