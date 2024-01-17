<template>
    <div
        class="flex flex-col items-center bg-indigo-700 fixed top-0 left-0 h-screen p-5 duration-150 md:sticky max-md:z-40"
        :class="showMenu ? 'max-md:translate-x-0' : 'max-md:-translate-x-full'"
    >
        <p class="text-white text-4xl font-semibold">mD</p>
        <div class="flex flex-col gap-4 my-auto">
            <Link
                v-for="(link, i) in links"
                :key="i"
                :href="link.link"
                :data-tooltip="link.tooltip"
                class="link"
                :class="{ active: link.active.includes($page.url) }"
            >
                <component :is="link.icon"></component>
                <span class="text-lg">{{ link.text }}</span>
            </Link>
        </div>
        <Link
            :href="`/${$page.props.user.username}`"
            data-tooltip="Profile"
            class="link mb-3"
            :class="{
                active: [
                    `/${$page.props.user.username}`,
                    `/${$page.props.user.username}?tab=posts`,
                ].includes($page.url),
            }"
        >
            <img
                :src="$page.props.user.avatar"
                alt="User avatar"
                v-if="$page.props.user.avatar"
                class="h-12 w-12 object-cover rounded-lg"
            />
            <UserOutlined class="bg-slate-200/75 rounded-full p-1.5" v-else />
            <span class="text-lg">Profile</span>
        </Link>
        <Link href="/security/logout" data-tooltip="Logout" class="link">
            <LogoutOutlined />
            <span class="text-lg">Logout</span>
        </Link>
    </div>
    <MenuOutlined class="mobileMenu" @click="toggleMenu" v-if="!showMenu" />
    <CloseOutlined class="mobileMenu" @click="toggleMenu" v-else />
</template>

<script setup>
import { ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
    UserOutlined,
    MenuOutlined,
    CloseOutlined,
    HomeOutlined,
    LogoutOutlined,
    FolderOutlined,
    QuestionOutlined,
} from "@ant-design/icons-vue";

const showMenu = ref(false);

const page = usePage();

const toggleMenu = () => (showMenu.value = !showMenu.value);

const links = ref([
    {
        link: "/",
        icon: HomeOutlined,
        text: "Homepage",
        active: ["/"],
    },
    {
        link: "/users",
        icon: UserOutlined,
        text: "Users",
        active: ["/users"],
    },
    {
        link: `/${page.props.user.username}?tab=groups`,
        icon: FolderOutlined,
        text: "Groups",
        active: [`/${page.props.user.username}?tab=groups`],
    },
    {
        link: "/requests",
        icon: QuestionOutlined,
        text: "Requests",
        active: ["/requests", "/requests?tab=received", "/requests?tab=sent"],
    },
]);
</script>

<style lang="postcss">
.mobileMenu {
    @apply fixed top-3 right-3 shadow-md bg-white rounded-md p-2 text-xl leading-[0] cursor-pointer md:hidden z-50;
}

.link {
    @apply text-white text-2xl leading-[0] duration-300 hover:bg-white !bg-opacity-10 p-4 rounded-md flex items-center justify-center gap-4 w-full;
}

.link.active {
    @apply pointer-events-none bg-indigo-950;
}
</style>
