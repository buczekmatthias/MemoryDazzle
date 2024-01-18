<template>
    <AppLayout>
        <div class="flex flex-col gap-4 p-3" v-if="post">
            <div class="flex items-center gap-4">
                <LeftOutlined
                    class="text-xl leading-none"
                    @click="redirectBack"
                />
                <p class="text-3xl font-semibold">Post</p>
            </div>
            <div class="flex flex-col shadow-md rounded-lg bg-white">
                <div class="flex items-center justify-between p-3">
                    <UserCard :user="post.group.owner" />
                    <Link
                        :href="`/groups/${post.group.id}`"
                        class="text-indigo-700 font-semibold"
                        :class="
                            $page.props.user.username ===
                            post.group.owner.username
                                ? 'mr-3'
                                : ''
                        "
                        >#{{ post.group.name }} {{ post.group.icon }}</Link
                    >
                </div>
                <div
                    class="flex flex-col gap-2 py-3 border-y border-solid border-y-gray-200"
                >
                    <div class="flex items-center gap-6 px-3">
                        <p class="text-gray-400 text-sm">
                            {{ post.created_at }}
                        </p>
                        <Link
                            :href="`/posts/edit/${post.id}`"
                            v-if="
                                $page.props.user.username ===
                                post.group.owner.username
                            "
                            class="text-lg leading-none text-indigo-700 ml-auto"
                        >
                            <EditOutlined />
                        </Link>
                        <DeleteOutlined
                            v-if="
                                $page.props.user.username ===
                                post.group.owner.username
                            "
                            class="text-lg leading-none text-red-700 cursor-pointer"
                            @click="handlePostDelete"
                        />
                    </div>
                    <p class="mx-3">{{ post.content }}</p>
                    <div
                        v-if="gridFilesCount > 0"
                        class="grid grid-cols-2 gap-1 mx-3 mt-2 h-[35rem] [&>*]:border [&>*]:border-solid [&>*]:border-gray-200"
                        :class="gridStyles[gridFilesCount - 1]"
                    >
                        <img
                            v-for="(img, i) in post.files.image"
                            :src="img.filename"
                            :key="i"
                            class="w-full h-full object-cover cursor-pointer"
                            @click="handleFileSelectPreview('image', img)"
                        />
                        <div
                            class="relative h-full w-full overflow-hidden cursor-pointer"
                            v-for="(vid, i) in post.files.video"
                            :key="i"
                            @click="handleFileSelectPreview('video', vid)"
                        >
                            <video
                                :src="vid.filename"
                                class="w-full h-full object-cover"
                            ></video>
                            <PlayCircleOutlined
                                class="text-5xl text-white/50 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20"
                            />
                        </div>
                    </div>
                    <FilePreview
                        v-if="showFilePreview"
                        :file="fileToPreview"
                        @closePreview="showFilePreview = false"
                    />
                    <div class="mx-3" v-if="post.files.file?.length > 0">
                        <FileObject
                            v-for="(file, i) in post.files.file"
                            :key="i"
                            :file="file"
                        />
                    </div>
                </div>
                <ReactionsComponent
                    :reactions="post.reactions"
                    :post_id="post.id"
                />
                <PostComments :comments="post.comments" :post_id="post.id" />
            </div>
        </div>
        <div
            class="flex flex-col items-center shadow-md rounded-lg bg-white p-4 py-6 my-auto mx-4"
            v-else
        >
            <p class="text-4xl font-semibold">Post not found</p>
            <p class="mt-2 mb-6">Possibly got deleted</p>
            <Link
                href="/"
                class="bg-indigo-700 text-white p-3 rounded-lg duration-300 hover:bg-indigo-600"
                >Get back to homepage</Link
            >
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import {
    DeleteOutlined,
    EditOutlined,
    LeftOutlined,
    PlayCircleOutlined,
} from "@ant-design/icons-vue";
import AppLayout from "../../Layouts/AppLayout.vue";
import ReactionsComponent from "../../Components/ReactionsComponent.vue";
import FileObject from "../../Components/FileObject.vue";
import FilePreview from "../../Components/FilePreview.vue";
import PostComments from "../../Components/PostComments.vue";
import UserCard from "../../Components/UserCard.vue";

const props = defineProps({
    post: Object,
});

const gridFilesCount = ref(
    (props.post?.files.image?.length || 0) +
        (props.post?.files.video?.length || 0)
);
const gridStyles = ref([
    "grid-cols-1 grid-rows-1 [&>*]:rounded-lg",
    "grid-cols-2 grid-rows-1 [&>*:first-child]:rounded-l-lg [&>*:last-child]:rounded-r-lg",
    "grid-cols-2 grid-rows-2 [&>*:first-child]:rounded-tl-lg [&>*:nth-child(2)]:rounded-bl-lg [&>*:last-child]:rounded-r-lg [&>*:last-child]:row-span-full [&>*:last-child]:col-start-2",
    "grid-cols-2 grid-rows-2 [&>*:first-child]:rounded-tl-lg [&>*:nth-child(2)]:rounded-tr-lg [&>*:nth-child(3)]:rounded-bl-lg [&>*:last-child]:rounded-br-lg",
]);

const showFilePreview = ref(false);
const fileToPreview = ref(null);

const redirectBack = () => {
    router.get("/");
};

const handleFileSelectPreview = (type, file) => {
    fileToPreview.value = {
        type: type,
        ...file,
    };

    showFilePreview.value = true;
};

const handlePostDelete = () => {
    router.delete(`/posts/delete/${props.post.id}`);
};
</script>

<style lang="postcss">
.content-items {
    @apply flex items-center justify-center gap-2 p-1 bg-slate-100;
}
</style>
