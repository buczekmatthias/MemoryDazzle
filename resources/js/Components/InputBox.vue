<template>
    <div class="flex flex-col gap-2">
        <label :for="id"
            >{{ label }}
            <span
                v-if="required === false"
                class="text-sm text-gray-400 italic font-light"
                >(Optional)</span
            ></label
        >
        <input
            :type="type"
            :required="required"
            :id="id"
            :placeholder="placeholder || label"
            v-model="inputValue"
            class="border border-solid border-gray-300 rounded-md p-2 outline-none focus:border-gray-500"
            :class="[
                errors.length > 0
                    ? 'border-red-600 text-red-600 focus:border-red-400'
                    : '',
                readonly
                    ? 'cursor-not-allowed bg-slate-500/15 border-slate-500'
                    : '',
            ]"
            :readonly="readonly"
            @input="handleInputValueChange"
        />
        <p class="text-sm text-red-600 font-semibold" v-if="errors.length > 0">
            {{ errors[0] }}
        </p>
    </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    label: String,
    id: String,
    value: { type: String, default: "" },
    placeholder: { type: String, default: "" },
    required: { type: Boolean, default: true },
    type: { type: String, default: "text" },
    errors: { type: Array, default: [] },
    readonly: { type: Boolean, default: false },
});

const inputValue = ref(props.value);

const emit = defineEmits(["update:modelValue"]);

const handleInputValueChange = (e) => {
    emit("update:modelValue", inputValue.value);
};
</script>
