<template>
    <AppLayout>
        <div class="flex flex-col gap-4 p-3 w-full max-w-6xl mx-auto">
            <p class="text-3xl font-semibold">
                <span v-if="list.tab === 'followers'">Followers</span>
                <span v-else>Following</span>
            </p>
            <div class="flex flex-col gap-4 bg-white rounded-xl shadow-md p-3">
                <div
                    class="sticky top-0 bg-white border-b border-solid border-b-gray-300 py-2 flex items-center gap-2"
                >
                    <Link
                        href="?tab=followers"
                        class="px-2 py-1 rounded-lg bg-gray-200"
                        :class="
                            list.tab === 'followers'
                                ? '!bg-indigo-700 text-white'
                                : ''
                        "
                        >Followers</Link
                    >
                    <Link
                        href="?tab=following"
                        class="px-2 py-1 rounded-lg bg-gray-200"
                        :class="
                            list.tab === 'following'
                                ? '!bg-indigo-700 text-white'
                                : ''
                        "
                        >Following</Link
                    >
                </div>
                <div
                    class="flex flex-col gap-2"
                    v-if="Object.keys(list.users.data).length > 0"
                >
                    <div
                        class="flex items-center justify-between"
                        v-for="(user, i) in list.users.data"
                        :key="i"
                    >
                        <UserCard :user="user" />
                        <ButtonComponent
                            v-if="
                                list.tab === 'following' &&
                                list.user === $page.props.user.username
                            "
                            @click="handleFollowButton(user)"
                        >
                            Unfollow
                        </ButtonComponent>
                    </div>
                    <Pagination :pagination="list.users" :shadow="false" />
                </div>
                <p v-else>Nothing to show here</p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import AppLayout from "../../Layouts/AppLayout.vue";
import Pagination from "../../components/Pagination.vue";
import UserCard from "../../Components/UserCard.vue";
import ButtonComponent from "../../Components/ButtonComponent.vue";

defineProps({
    list: Object,
});

const handleFollowButton = (user) => {
    if (user.isPrivate) {
        if (
            confirm(
                "This profile is private. If you proceed, you'll lose access to it's content. Are you sure to unfollow?"
            )
        ) {
            router.post(`/follow/${user.username}`, {
                preserveScroll: true,
            });
        }
    } else {
        router.post(`/follow/${user.username}`, {
            preserveScroll: true,
        });
    }
};
</script>
