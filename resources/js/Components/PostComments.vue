<template>
    <div
        class="flex flex-col gap-2 border-t border-t-gray-200 border-solid p-3"
    >
        <p class="text-xl font-semibold">Comments</p>
        <form
            @submit.prevent="handleNewCommentForm"
            class="flex flex-col items-end"
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
            <ButtonComponent classes="px-5 py-2">Post</ButtonComponent>
        </form>
        <div
            class="flex flex-col gap-2"
            v-if="Object.keys(comments.data).length > 0"
        >
            <div
                class="flex flex-col items-start gap-2 border border-solid border-gray-200 rounded-lg p-3"
                v-for="(comment, i) in comments.data"
                :key="i"
            >
                <div class="flex justify-between items-start w-full">
                    <UserCard :user="comment.author" />
                    <p class="text-gray-400 text-sm">
                        {{ comment.created_at }}
                    </p>
                </div>
                <p class="w-full">{{ comment.content }}</p>
                <p
                    class="text-red-600 text-sm cursor-pointer"
                    v-if="comment.author.username === $page.props.user.username"
                    @click="handleCommentDelete(comment.id)"
                >
                    Delete
                </p>
            </div>
            <Pagination :pagination="comments" :sticky="true" />
        </div>
        <p v-else>Nothing to show in this section</p>
    </div>
</template>

<script setup>
import { router, useForm } from "@inertiajs/vue3";
import EmojiPicker from "vue3-emoji-picker";
import "vue3-emoji-picker/css";
import Pagination from "./Pagination.vue";
import UserCard from "./UserCard.vue";
import ButtonComponent from "./ButtonComponent.vue";

const props = defineProps({
    comments: Object,
    post_id: String,
});

const form = useForm({
    content: "",
    post_id: props.post_id,
});

const handleNewCommentForm = () => {
    form.post("/comments/create", {
        preserveScroll: true,
        onSuccess: () => {
            // No other way found, author of picker not responding
            const contentArea = document.querySelector(
                ".v3-emoji-picker-textarea"
            );
            contentArea.value = "";
        },
    });
};

const handleCommentDelete = (id) => {
    router.delete(`/comments/delete/${id}`, {
        preserveScroll: true,
    });
};
</script>
