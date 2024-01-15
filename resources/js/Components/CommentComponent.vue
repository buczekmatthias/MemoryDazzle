<template>
    <div
        class="flex flex-col items-start gap-2 border border-solid border-gray-200 rounded-lg p-3"
    >
        <div class="flex justify-between items-start w-full">
            <UserCard :user="comment.author" />
            <p class="text-gray-400 text-sm">
                {{ comment.created_at }}
            </p>
        </div>
        <p class="w-full">{{ comment.content }}</p>
        <div class="flex items-center gap-4 w-full">
            <p
                class="text-red-600 text-sm cursor-pointer"
                v-if="comment.author.username === $page.props.user.username"
                @click="handleCommentDelete(comment.id)"
            >
                Delete
            </p>
            <Link
                v-if="comment.post_id"
                :href="`/posts/${comment.post_id}`"
                class="text-sm text-gray-400 ml-auto"
            >
                See post
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import UserCard from "./UserCard.vue";

defineProps({
    comment: Object,
});
</script>
