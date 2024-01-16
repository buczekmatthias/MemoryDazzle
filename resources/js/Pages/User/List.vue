<template>
    <AppLayout>
        <div class="flex flex-col gap-4 p-3">
            <p class="text-3xl font-semibold">Users</p>
            <div class="flex flex-col bg-white rounded-xl shadow-md p-3">
                <div
                    v-for="(user, i) in users.data"
                    :key="i"
                    class="flex justify-between items-center even:border-y even:border-solid even:border-y-gray-300 py-4"
                >
                    <UserCard :user="user" />
                    <ButtonComponent
                        :class="
                            ['Following', 'Requested'].includes(user.status)
                                ? 'bg-transparent border border-solid border-gray-300 !text-gray-400 hover:bg-gray-100/85'
                                : ''
                        "
                        @click="handleFollowButton(user)"
                    >
                        {{ user.status }}
                    </ButtonComponent>
                </div>
                <Pagination :pagination="users" :shadow="false" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import AppLayout from "../../Layouts/AppLayout.vue";
import Pagination from "../../components/Pagination.vue";
import UserCard from "../../Components/UserCard.vue";
import ButtonComponent from "../../Components/ButtonComponent.vue";

defineProps({
    users: Object,
});

const handleFollowButton = (user) => {
    if (user.status === "Following" && user.isPrivate) {
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
