<template>
    <AppLayout>
        <div class="flex flex-col gap-4 p-3 w-full max-w-6xl mx-auto">
            <p class="text-3xl font-semibold">Group content</p>
            <div class="flex flex-col gap-4 bg-white rounded-xl shadow-md p-3">
                <div class="grid grid-cols-[1fr_auto] gap-2">
                    <p class="flex gap-1.5">
                        <span>{{ group.icon }} {{ group.name }} of</span>
                        <Link
                            :href="`/${group.owner.username}`"
                            class="text-indigo-700 truncate"
                        >
                            <span v-if="group.owner.displayname"
                                >{{ group.owner.displayname }} (@{{
                                    group.owner.username
                                }})</span
                            >
                            <span v-else>@{{ group.owner.userna }}</span>
                        </Link>
                    </p>
                    <Link
                        :href="`/groups/${group.id}/edit`"
                        class="text-lg leading-[0] text-indigo-700 cursor-pointer"
                        v-if="
                            group.owner.username === $page.props.user.username
                        "
                    >
                        <EditOutlined />
                    </Link>
                </div>
                <div
                    class="flex flex-col gap-3"
                    v-if="Object.keys(group.posts.data).length > 0"
                >
                    <PostComponent
                        v-for="(post, i) in group.posts.data"
                        :key="i"
                        :post="post"
                        :hasShadow="false"
                        :withHeader="false"
                    />
                    <Pagination :shadow="false" :pagination="group.posts" />
                </div>
                <p v-else>There's nothing to show in this group</p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import AppLayout from "../../Layouts/AppLayout.vue";
import { EditOutlined } from "@ant-design/icons-vue";
import Pagination from "../../components/Pagination.vue";
import PostComponent from "../../components/PostComponent.vue";

defineProps({
    group: Object,
});
</script>
