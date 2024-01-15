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
        <GroupSelect
            v-model="form.group"
            width="w-1/2"
            :groups="$page.props.groups"
        />
        <form
            @submit.prevent="handleFormSubmit"
            class="flex items-center gap-2"
        >
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
            <ButtonComponent classes="px-5 py-2" :disabled="form.processing">
                <span v-if="form.processing">Posting...</span>
                <span v-else>Post</span>
            </ButtonComponent>
        </form>
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
import GroupSelect from "./GroupSelect.vue";

const page = usePage();

const showFileInput = ref(false);

const form = useForm({
    content: "",
    group: page.props.groups[0].id,
    files: null,
});

const handleFormSubmit = () => {
    form.post(page.props.store_post_route, {
        onSuccess: () => {
            showFileInput.value = false;
            // No other way found, author of picker not responding
            const contentArea = document.querySelector(
                ".v3-emoji-picker-textarea"
            );
            contentArea.value = "";
        },
        onError: () => {
            handleInputFile();
        },
    });
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
