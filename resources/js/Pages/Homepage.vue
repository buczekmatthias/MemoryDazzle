<template>
    <AppLayout>
        <div class="flex flex-col gap-4 p-3 w-full max-w-6xl mx-auto">
            <p class="text-3xl font-semibold">Home</p>
            <NewPost />
            <div class="flex flex-col gap-3">
                <p
                    class="mt-3 font-semibold text-2xl text-center"
                    v-if="$page.props.posts.data.length === 0"
                >
                    Your timeline is empty
                </p>
                <PostComponent
                    v-else
                    v-for="post in $page.props.posts.data"
                    :key="post"
                    :post="post"
                />
            </div>
            <Pagination
                v-if="$page.props.posts.data.length > 0"
                :pagination="$page.props.posts"
            />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
import NewPost from "../Components/NewPost.vue";
import Pagination from "../Components/Pagination.vue";
import PostComponent from "../Components/PostComponent.vue";
</script>
