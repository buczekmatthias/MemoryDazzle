<template>
    <div
        class="flex flex-col rounded-lg bg-white"
        :class="hasShadow ? 'shadow-md' : 'border border-solid border-gray-300'"
    >
        <div class="flex justify-between items-center p-3" v-if="withHeader">
            <UserCard :user="post.group.owner" />
            <Link
                :href="`/groups/${post.group.id}`"
                class="text-indigo-700 font-semibold"
                >#{{ post.group.name }} {{ post.group.icon }}</Link
            >
        </div>
        <Link
            :href="`/posts/${post.id}`"
            class="flex flex-col gap-2 border-solid"
            :class="
                withHeader
                    ? 'border-y border-y-gray-200'
                    : 'border-b border-b-gray-200'
            "
        >
            <div class="flex justify-between">
                <p class="text-gray-400 text-sm mx-3 mt-3">
                    {{ post.created_at }}
                </p>
            </div>
            <p class="mx-3">{{ post.content }}</p>
            <div class="grid grid-cols-[1fr_auto_1fr] gap-0.5 mt-2">
                <div class="content-items">
                    <p>
                        <span>😀</span>
                        <span class="-mx-3">😁</span>
                        <span>😂</span>
                    </p>
                    <p>{{ post.reactions_count }}</p>
                </div>
                <div class="content-items">
                    <FileOutlined />
                    <p>{{ post.files_count }}</p>
                </div>
                <div class="content-items">
                    <MessageOutlined class="text-lg leading-[0]" />
                    <p class="text-lg">{{ post.comments_count }}</p>
                </div>
            </div>
        </Link>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { MessageOutlined, FileOutlined } from "@ant-design/icons-vue";
import UserCard from "./UserCard.vue";

defineProps({
    post: Object,
    hasShadow: {
        type: Boolean,
        default: true,
    },
    withHeader: {
        type: Boolean,
        default: true,
    },
});
</script>

<style lang="postcss">
.content-items {
    @apply flex items-center justify-center gap-2 p-1 !px-3 lg:!px-6 bg-slate-100;
}
</style>
