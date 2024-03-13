<template>
    <AppLayout>
        <div class="flex flex-col gap-4 p-3 w-full max-w-6xl mx-auto">
            <p class="text-3xl font-semibold">Users</p>
            <div class="flex flex-col bg-white rounded-xl shadow-md p-3">
                <div
                    class="grid items-center gap-4 p-2 border border-solid border-gray-300 rounded-md"
                    :class="
                        query === ''
                            ? 'grid-cols-[1fr_auto]'
                            : 'grid-cols-[auto_1fr_auto]'
                    "
                >
                    <CloseOutlined
                        v-if="query !== ''"
                        class="cursor-pointer text-lg leading-[0]"
                        @click="query = ''"
                    />
                    <input
                        type="text"
                        placeholder="Search by username..."
                        class="outline-transparent"
                        v-model="query"
                    />
                    <SearchOutlined
                        class="cursor-pointer text-xl leading-[0]"
                        @click="handleSearch"
                    />
                </div>
                <div class="" v-if="Object.keys(users.data).length > 0">
                    <div
                        v-for="(user, i) in users.data"
                        :key="i"
                        class="flex items-center even:border-y even:border-solid even:border-y-gray-300 py-4"
                    >
                        <UserCard :user="user" class="mr-auto" />
                        <p
                            v-if="user.following_count === 1"
                            class="mr-5 text-gray-400 text-sm"
                        >
                            Follows you
                        </p>
                        <ButtonComponent
                            :class="
                                ['Following', 'Requested'].includes(
                                    getFollowingStatus(user)
                                )
                                    ? 'bg-transparent border border-solid border-gray-300 !text-gray-400 hover:bg-gray-100/85'
                                    : ''
                            "
                            @click="handleFollowButton(user)"
                        >
                            {{ getFollowingStatus(user) }}
                        </ButtonComponent>
                    </div>
                    <Pagination :pagination="users" :shadow="false" />
                </div>
                <p class="mt-5 mb-2 text-center text-xl" v-else>
                    Nothing to display
                </p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import AppLayout from "../../Layouts/AppLayout.vue";
import Pagination from "../../components/Pagination.vue";
import UserCard from "../../Components/UserCard.vue";
import ButtonComponent from "../../Components/ButtonComponent.vue";
import { CloseOutlined, SearchOutlined } from "@ant-design/icons-vue";

const props = defineProps({
    users: Object,
    q: String,
});

const query = ref(props.q || "");

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

const getFollowingStatus = (user) => {
    return user.followed_by_count === 1
        ? "Following"
        : user.received_follow_requests_count === 1
        ? "Requested"
        : "Follow";
};

const handleSearch = () => {
    if (query.value === "") {
        router.get("/users");
    } else {
        router.get(`?q=${query.value}`);
    }
};
</script>
