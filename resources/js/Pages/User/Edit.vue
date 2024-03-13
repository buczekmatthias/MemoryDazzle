<template>
    <AppLayout>
        <div class="flex flex-col gap-4 p-3 w-full max-w-6xl mx-auto">
            <p class="text-3xl font-semibold">Edit profile</p>
            <form
                @submit.prevent="handleFormSubmit"
                class="flex flex-col gap-4 bg-white rounded-xl shadow-md p-3"
            >
                <div class="flex flex-col gap-2">
                    <label
                        >Display name
                        <span class="text-sm text-gray-400 italic font-light"
                            >(Optional)</span
                        ></label
                    >
                    <EmojiPicker
                        :lazy="true"
                        class="picker"
                        :native="true"
                        :disable-skin-tones="true"
                        :hide-group-names="true"
                        picker-type="input"
                        v-model:text="form.displayname"
                    />
                </div>
                <InputBox
                    label="Username"
                    id="username"
                    v-model="form.username"
                    :placeholder="form.username"
                    :errors="form.errors.username"
                    :value="form.username"
                />
                <InputBox
                    label="E-mail"
                    id="email"
                    type="email"
                    v-model="form.email"
                    :placeholder="form.email"
                    :errors="form.errors.email"
                    :value="form.email"
                />
                <div class="grid grid-cols-2 gap-y-2">
                    <p class="col-span-full">Profile visibility</p>
                    <label
                        for="public"
                        class="radio rounded-l-lg !border-r-0"
                        :class="form.visibility === 'public' ? 'checked' : ''"
                        >Public</label
                    >
                    <input
                        type="radio"
                        name="visibility"
                        id="public"
                        v-model="form.visibility"
                        value="public"
                        class="hidden"
                        :checked="form.visibility === 'public'"
                    />
                    <label
                        for="private"
                        class="radio rounded-r-lg !border-l-0"
                        :class="form.visibility === 'private' ? 'checked' : ''"
                        >Private</label
                    >
                    <input
                        type="radio"
                        name="visibility"
                        id="private"
                        v-model="form.visibility"
                        value="private"
                        class="hidden"
                        :checked="form.visibility === 'private'"
                    />
                </div>
                <InputFile
                    :required="false"
                    v-model="form.avatar"
                    accepted="image/jpeg,image/svg,image/png"
                    label="Select your avatar (jpeg,jpg,svg,png)"
                    :errors="form.errors.avatar"
                    :hasFile="form.avatar !== null"
                    ref="file"
                />
                <div
                    class="flex items-center justify-between"
                    v-if="profile.avatar"
                >
                    <InputCheckbox
                        v-model="form.only_delete_avatar"
                        :checked="form.only_delete_avatar"
                        >Only delete avatar</InputCheckbox
                    >
                    <p
                        class="text-indigo-700 cursor-pointer"
                        @click="previewAvatar = true"
                    >
                        Preview current avatar
                    </p>
                    <FilePreview
                        :file="{ type: 'image', filename: profile.avatar }"
                        :withDownload="false"
                        v-if="previewAvatar"
                        @closePreview="previewAvatar = false"
                    />
                </div>
                <ButtonComponent :disabled="form.processing">
                    Update profile
                </ButtonComponent>
                <div
                    class="border-t border-solid border-t-gray-300 mt-3 pt-3 flex flex-col gap-2"
                >
                    <p class="text-3xl font-semibold">Delete profile</p>
                    <p class="text-sm text-gray-500">
                        This process can't be undone. Be sure if you want to do
                        this action.
                    </p>
                    <ButtonComponent
                        @click="handleDeleteAccountButton"
                        type="button"
                        classes="bg-red-600 w-full hover:bg-red-700 mt-3"
                    >
                        Delete
                    </ButtonComponent>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import AppLayout from "../../Layouts/AppLayout.vue";
import InputBox from "../../Components/InputBox.vue";
import InputFile from "../../Components/InputFile.vue";
import InputCheckbox from "../../Components/InputCheckbox.vue";
import ButtonComponent from "../../Components/ButtonComponent.vue";
import EmojiPicker from "vue3-emoji-picker";
import "vue3-emoji-picker/css";
import FilePreview from "../../Components/FilePreview.vue";

const props = defineProps({
    profile: Object,
});

const previewAvatar = ref(false);

const file = ref(null);

const form = useForm({
    displayname: props.profile.displayname || "",
    username: props.profile.username,
    email: props.profile.email,
    avatar: null,
    only_delete_avatar: false,
    visibility: props.profile.visibility,
});

const handleFormSubmit = () => {
    form.post("/edit-profile", {
        preserveState: false,
        onSuccess: () => {
            file.value.$refs.inp.value = "";
            form.reset();
        },
    });
};

const handleDeleteAccountButton = () => {
    router.delete("/delete-profile");
};
</script>

<style lang="postcss">
.radio {
    @apply text-center p-2 border-4 border-solid border-gray-300 cursor-pointer;
}
.checked {
    @apply border-indigo-700 bg-indigo-700/15 text-indigo-700 font-semibold cursor-default;
}
.v3-emoji-picker-input {
    @apply border border-solid !border-gray-300 rounded-md !pl-2 p-2 outline-none focus:!border-gray-500;
}
</style>
