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
            class="border border-solid border-gray-300 rounded-md p-2 outline-none focus:border-gray-500"
            :class="[
                errors.length > 0
                    ? 'border-red-600 text-red-600 focus:border-red-400'
                    : '',
                readonly
                    ? 'cursor-not-allowed bg-slate-500/15 border-slate-500'
                    : '',
            ]"
            :value="value"
            :readonly="readonly"
            @input="handleInputValueChange"
        />
        <p class="text-sm text-red-600 font-semibold" v-if="errors.length > 0">
            {{ errors[0] }}
        </p>
    </div>
</template>

<script setup>
defineProps({
    label: String,
    id: String,
    placeholder: { type: String, default: "" },
    required: { type: Boolean, default: true },
    type: { type: String, default: "text" },
    errors: { type: Array, default: [] },
    value: { type: String, default: "" },
    readonly: { type: Boolean, default: false },
});

const emit = defineEmits(["update:modelValue"]);

const handleInputValueChange = (e) => {
    emit("update:modelValue", e.target.value);
};
</script>
