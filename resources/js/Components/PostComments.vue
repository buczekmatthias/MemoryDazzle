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
            <ButtonComponent classes="px-5 py-2" :disabled="form.processing">
                <span v-if="form.processing">Posting...</span>
                <span v-else>Post</span>
            </ButtonComponent>
        </form>
        <div
            class="flex flex-col gap-2"
            v-if="Object.keys(comments.data).length > 0"
        >
            <CommentComponent
                v-for="(comment, i) in comments.data"
                :key="i"
                :comment="comment"
            />
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
import CommentComponent from "./CommentComponent.vue";
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
