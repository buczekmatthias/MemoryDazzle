<template>
    <AppLayout>
        <div class="p-3 flex flex-col gap-4">
            <Link :href="`/posts/${post.id}`" class="flex items-center gap-3">
                <LeftOutlined class="text-xl leading-none" />
                <p class="text-3xl font-semibold">Cancel edit</p>
            </Link>
            <form
                @submit.prevent="handleUpdateForm"
                class="flex flex-col gap-4 bg-white rounded-xl shadow-md p-3"
            >
                <InputBox
                    id="id"
                    label="Post id"
                    :readonly="true"
                    :placeholder="post.id"
                />
                <EmojiPicker
                    :lazy="true"
                    class="picker"
                    :native="true"
                    :disable-skin-tones="true"
                    :hide-group-names="true"
                    picker-type="textarea"
                    v-model:text="form.content"
                />
                <GroupSelect v-model="form.group_id" :groups="groups" />
                <div class="flex flex-col">
                    <p class="text-xl font-semibold">Files</p>
                    <p class="text-sm text-gray-400 mt-1 mb-3">
                        If you accidentally deleted file from list, reload page
                    </p>
                    <div class="flex flex-col" v-if="form.files.length > 0">
                        <div
                            class="flex items-center justify-between even:bg-slate-100 px-1.5 py-2"
                            v-for="(file, i) in form.files"
                            :key="i"
                        >
                            <div
                                class="file-item"
                                :class="
                                    ['image', 'video'].includes(file.type)
                                        ? 'cursor-pointer text-indigo-700'
                                        : ''
                                "
                                @click="handleFilePreview(file)"
                            >
                                <FileImageOutlined
                                    v-if="file.type === 'image'"
                                />
                                <VideoCameraOutlined
                                    v-else-if="file.type === 'video'"
                                />
                                <FileOutlined v-else />
                                <p class="">{{ file.name }}</p>
                            </div>
                            <CloseOutlined @click="removeFile(file)" />
                        </div>
                        <FilePreview
                            v-if="showFilePreview"
                            :file="fileToPreview"
                            @closePreview="showFilePreview = false"
                        />
                    </div>
                    <p v-else>No files</p>
                </div>
                <ButtonComponent>Update post</ButtonComponent>
                <p
                    class="border-2 border-solid border-red-600 bg-red-600/20 text-red-600 rounded-lg flex items-center justify-center p-4 font-semibold"
                    v-if="form.errors.error"
                >
                    {{ form.errors.error }}
                </p>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import AppLayout from "../../Layouts/AppLayout.vue";
import {
    CloseOutlined,
    FileImageOutlined,
    FileOutlined,
    LeftOutlined,
    VideoCameraOutlined,
} from "@ant-design/icons-vue";
import InputBox from "../../Components/InputBox.vue";
import GroupSelect from "../../Components/GroupSelect.vue";
import EmojiPicker from "vue3-emoji-picker";
import "vue3-emoji-picker/css";
import FilePreview from "../../Components/FilePreview.vue";
import ButtonComponent from "../../components/ButtonComponent.vue";

const showFilePreview = ref(false);
const fileToPreview = ref({});

const props = defineProps({
    post: Object,
    groups: Object,
});

const form = useForm({
    content: props.post.content,
    group_id: props.post.group_id,
    files: props.post.files,
});

const handleFilePreview = (file) => {
    if (file.type === "file") {
        return;
    }

    fileToPreview.value = file;

    showFilePreview.value = true;
};

const removeFile = (file) => {
    form.files.splice(
        form.files.findIndex((f) => f.id === file.id),
        1
    );
};

const handleUpdateForm = () => {
    form.post(`/posts/edit/${props.post.id}`, {
        onSuccess: () => {
            router.get(`/posts/${props.post.id}`);
        },
        // onError: () => {
        //     error.value = "Couldn't update post. Please try again later.";
        // },
    });
};
</script>

<style lang="postcss" scoped>
.file-item {
    @apply flex items-center gap-4;
}
</style>
