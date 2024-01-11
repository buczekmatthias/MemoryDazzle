<template>
    <div class="flex flex-col shadow-md rounded-lg bg-white">
        <div class="flex justify-between items-center p-3">
            <Link
                :href="`/${post.group.owner.username}`"
                class="flex gap-2 items-center"
            >
                <img
                    :src="post.group.owner.avatar"
                    alt="User avatar"
                    v-if="post.group.owner.avatar"
                    class="h-10 w-10 object-cover rounded-full border-2 border-solid border-indigo-700/25"
                />
                <UserOutlined
                    class="text-xl leading-[0] bg-slate-200/75 rounded-full p-2.5"
                    v-else
                />
                <div>
                    <p
                        class="font-semibold leading-none"
                        v-if="post.group.owner.displayname"
                    >
                        {{ post.group.owner.displayname }}
                    </p>
                    <p
                        class="text-gray-400 text-sm font-light"
                        :class="
                            !post.group.owner.displayname ? 'text-base' : ''
                        "
                    >
                        @{{ post.group.owner.username }}
                    </p>
                </div>
            </Link>
            <Link href="" class="text-indigo-700 font-semibold"
                >#{{ post.group.name }} {{ post.group.icon }}</Link
            >
        </div>
        <div
            class="flex flex-col gap-2 border-y border-solid border-y-gray-200"
        >
            <p class="text-gray-400 text-sm mx-3 mt-3">{{ post.created_at }}</p>
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
        </div>
        <ReactionsComponent :reactions="post.reactions" :post_id="post.id" />
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import {
    UserOutlined,
    MessageOutlined,
    FileOutlined,
    VideoCameraOutlined,
    FileImageOutlined,
} from "@ant-design/icons-vue";
import ReactionsComponent from "./ReactionsComponent.vue";

defineProps({
    post: Object,
});
</script>

<style lang="postcss">
.content-items {
    @apply flex items-center justify-center gap-2 p-1 bg-slate-100;
}
</style>
