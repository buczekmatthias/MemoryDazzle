<template>
    <div
        class="bg-white shadow-md rounded-xl p-2 flex flex-wrap gap-2 justify-between items-center relative"
    >
        <EmojiPicker
            :lazy="true"
            class="picker"
            :native="true"
            :disable-skin-tones="true"
            :hide-group-names="true"
            picker-type="textarea"
            v-model:text="form.content"
        />
        <select
            v-model="form.group"
            class="outline-none border border-solid border-gray-300 rounded-lg cursor-pointer w-1/2 p-1"
        >
            <option
                v-for="(group, i) in $page.props.groups"
                :value="group.id"
                :key="group.id"
            >
                {{ group.icon }} {{ group.name }}
            </option>
        </select>
        <div class="flex items-center gap-2">
            <FileOutlined
                @click="handleInputFile"
                class="text-xl leading-[0] cursor-pointer hover:bg-slate-300/40 p-2 rounded-full"
            />
            <div
                class="absolute top-[105%] left-0 w-full bg-gray-100 p-3 rounded-xl"
                :class="showFileInput ? 'grid grid-cols-1' : 'hidden'"
            >
                <InputFile
                    label="Select up to 4 files"
                    :errors="form.errors.files"
                    :limit="4"
                    v-model="form.files"
                />
            </div>
            <ButtonComponent classes="px-5 py-2" :callback="handleFormSubmit"
                >Post</ButtonComponent
            >
        </div>
        <p class="text-red-600 font-semibold" v-if="form.errors.generic">
            {{ form.errors.generic }}
        </p>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { FileOutlined } from "@ant-design/icons-vue";
import EmojiPicker from "vue3-emoji-picker";
import "vue3-emoji-picker/css";
import InputFile from "./InputFile.vue";
import ButtonComponent from "./ButtonComponent.vue";

const page = usePage();

const showFileInput = ref(false);

const form = useForm({
    content: null,
    group: page.props.groups[0].id,
    files: null,
});

const handleFormSubmit = () => {
    form.post(page.props.store_post_route);
    form.reset();
    handleInputFile();
};

const handleInputFile = () => {
    showFileInput.value = !showFileInput.value;
};
</script>

<style lang="postcss">
.picker {
    @apply w-full;
}
.picker textarea {
    @apply p-2 outline-none border border-solid border-gray-300 !h-32 !resize-none rounded-lg;
}
</style>
