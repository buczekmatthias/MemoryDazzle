<template>
    <Link :href="`/${user.username}`" class="flex gap-2 items-center">
        <img
            :src="user.avatar"
            alt="User avatar"
            v-if="user.avatar"
            class="h-10 w-10 object-cover rounded-full border-2 border-solid border-indigo-700/25"
        />
        <UserOutlined
            class="text-xl leading-[0] bg-slate-200/75 rounded-full p-2.5"
            v-else
        />
        <div>
            <p class="font-semibold leading-none" v-if="user.displayname">
                {{ user.displayname }}
            </p>
            <p
                class="text-gray-400 text-sm font-light"
                :class="!user.displayname ? 'text-base' : ''"
            >
                @{{ user.username }}
            </p>
        </div>
    </Link>
</template>

<script setup>
import { UserOutlined } from "@ant-design/icons-vue";
import { Link } from "@inertiajs/vue3";

defineProps({
    user: Object,
});
</script>
