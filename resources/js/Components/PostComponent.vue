<template>
    <div
        class="flex flex-col rounded-lg bg-white"
        :class="hasShadow ? 'shadow-md' : 'border border-solid border-gray-300'"
    >
        <div class="flex justify-between items-center p-3">
            <UserCard :user="post.group.owner" />
            <Link href="" class="text-indigo-700 font-semibold"
                >#{{ post.group.name }} {{ post.group.icon }}</Link
            >
        </div>
        <Link
            :href="`/posts/${post.id}`"
            class="flex flex-col gap-2 border-y border-solid border-y-gray-200"
        >
            <div class="flex justify-between">
                <p class="text-gray-400 text-sm mx-3 mt-3">
                    {{ post.created_at }}
                </p>
                <div
                    class="text-gray-400 text-sm mx-3 mt-3 flex items-center gap-2"
                >
                    <span>Click here to open</span>
                    <ExpandOutlined />
                </div>
            </div>
            <p class="mx-3">{{ post.content }}</p>
            <div class="grid grid-cols-4 gap-0.5 mt-2">
                <div class="content-items">
                    <FileImageOutlined />
                    <p>{{ post.files.image?.length || 0 }}</p>
                </div>
                <div class="content-items">
                    <VideoCameraOutlined />
                    <p>{{ post.files.video?.length || 0 }}</p>
                </div>
                <div class="content-items">
                    <FileOutlined />
                    <p>{{ post.files.file?.length || 0 }}</p>
                </div>
                <div class="content-items">
                    <MessageOutlined class="text-lg leading-[0]" />
                    <p class="text-lg">{{ post.comments_count }}</p>
                </div>
            </div>
        </Link>
        <ReactionsComponent :reactions="post.reactions" :post_id="post.id" />
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import {
    MessageOutlined,
    FileOutlined,
    VideoCameraOutlined,
    FileImageOutlined,
    ExpandOutlined,
} from "@ant-design/icons-vue";
import ReactionsComponent from "./ReactionsComponent.vue";
import UserCard from "./UserCard.vue";

defineProps({
    post: Object,
    hasShadow: {
        type: Boolean,
        default: true,
    },
});
</script>

<style lang="postcss">
.content-items {
    @apply flex items-center justify-center gap-2 p-1 bg-slate-100;
}
</style>
