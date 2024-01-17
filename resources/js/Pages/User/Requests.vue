<template>
    <AppLayout>
        <div class="flex flex-col gap-4 p-3">
            <p class="text-3xl font-semibold">Follow requests</p>
            <div class="flex flex-col gap-4 bg-white rounded-xl shadow-md p-3">
                <div
                    class="sticky top-0 bg-white border-b border-solid border-b-gray-300 py-2 flex items-center gap-2"
                >
                    <Link
                        href="?tab=received"
                        class="px-2 py-1 rounded-lg bg-gray-200"
                        :class="
                            tab === 'received'
                                ? '!bg-indigo-700 text-white'
                                : ''
                        "
                        >Received</Link
                    >
                    <Link
                        href="?tab=sent"
                        class="px-2 py-1 rounded-lg bg-gray-200"
                        :class="
                            tab === 'sent' ? '!bg-indigo-700 text-white' : ''
                        "
                        >Sent</Link
                    >
                </div>
                <div
                    v-for="(request, i) in requests"
                    :key="i"
                    class="flex justify-between items-center even:border-y even:border-solid even:border-y-gray-300 py-3 last-of-type:border-0"
                >
                    <Usercard :user="request" />
                    <ButtonComponent
                        v-if="tab === 'sent'"
                        classes="bg-transparent border border-solid border-gray-300 !text-gray-400 hover:bg-gray-100/85"
                        @click="handleFollowButton(request)"
                    >
                        Requested
                    </ButtonComponent>
                    <div class="flex gap-2" v-else>
                        <ButtonComponent
                            @click="handleRequest('accept', request.username)"
                        >
                            Accept
                        </ButtonComponent>
                        <ButtonComponent
                            classes="bg-transparent border border-solid border-gray-300 !text-gray-400 hover:bg-gray-100/85"
                            @click="handleRequest('refuse', request.username)"
                        >
                            Refuse
                        </ButtonComponent>
                    </div>
                </div>
                <p v-if="Object.keys(requests).length === 0">
                    Nothing to show here
                </p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import AppLayout from "../../Layouts/AppLayout.vue";
import Usercard from "../../components/UserCard.vue";
import ButtonComponent from "../../components/ButtonComponent.vue";

defineProps({
    tab: String,
    requests: Object,
});

const handleFollowButton = (user) => {
    router.post(`/follow/${user.username}`, {
        preserveScroll: true,
    });
};

const handleRequest = (action, username) => {
    router.post(`/requests/${action}/${username}`, {
        preserveScroll: true,
    });
};
</script>
