<template>
    <AppLayout>
        <div class="flex flex-col gap-4 p-3">
            <p class="text-3xl font-semibold">User profile</p>
            <div
                class="flex flex-col gap-4 bg-white rounded-xl shadow-md px-3 py-5"
            >
                <div class="flex flex-col items-center gap-4">
                    <img
                        :src="profile.avatar"
                        alt="User avatar"
                        v-if="profile.avatar"
                        class="h-40 w-40 object-cover rounded-full border-2 border-solid border-indigo-700/25"
                    />
                    <UserOutlined
                        class="text-6xl leading-[0] bg-slate-200/75 rounded-full p-5"
                        v-else
                    />
                    <div class="flex flex-col items-center">
                        <p
                            class="font-semibold text-2xl leading-none"
                            v-if="profile.displayname"
                        >
                            {{ profile.displayname }}
                        </p>
                        <p
                            class="text-gray-400 text-lg font-light"
                            :class="!profile.displayname ? 'text-base' : ''"
                        >
                            @{{ profile.username }}
                        </p>
                        <ButtonComponent
                            class="my-3"
                            v-if="
                                profile.username === $page.props.user.username
                            "
                            :callback="toggleEditProfileModal"
                        >
                            Edit profile
                        </ButtonComponent>
                        <ButtonComponent
                            class="my-3"
                            :class="
                                ['following', 'pending'].includes(status)
                                    ? 'bg-transparent border border-solid border-gray-300 !text-gray-400 hover:bg-gray-100/85'
                                    : ''
                            "
                            v-else
                        >
                            <span class="" v-if="status === 'following'"
                                >Following</span
                            >
                            <span class="" v-else-if="status === 'pending'"
                                >Requested follow</span
                            >
                            <span class="" v-else>Follow</span>
                        </ButtonComponent>
                    </div>
                    <div
                        class="flex items-center divide-x [&>*]:px-7"
                        v-if="hasAccess"
                    >
                        <Link class="flex flex-col gap-2 items-center">
                            <p class="font-semibold">Followers</p>
                            <p class="font-light">
                                {{ profile.followed_by_count }}
                            </p>
                        </Link>
                        <Link class="flex flex-col gap-2 items-center">
                            <p class="font-semibold">Following</p>
                            <p class="font-light">
                                {{ profile.following_count }}
                            </p>
                        </Link>
                    </div>
                    <div class="flex items-center divide-x [&>*]:px-7" v-else>
                        <div class="flex flex-col gap-2 items-center">
                            <p class="font-semibold">Followers</p>
                            <p class="font-light">
                                {{ profile.followed_by_count }}
                            </p>
                        </div>
                        <div class="flex flex-col gap-2 items-center">
                            <p class="font-semibold">Following</p>
                            <p class="font-light">
                                {{ profile.following_count }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-4" v-if="hasAccess">
                    <div
                        class="flex items-center gap-3 border-y border-solid border-y-gray-200 p-2"
                    >
                        <Link
                            href="?tab=posts"
                            class="px-3 py-2 rounded-lg"
                            :class="
                                tab === 'posts'
                                    ? 'bg-indigo-700 text-white'
                                    : 'bg-gray-200/75'
                            "
                        >
                            Posts ({{ profile.posts_count }})
                        </Link>
                        <Link
                            href="?tab=comments"
                            class="px-3 py-2 rounded-lg"
                            :class="
                                tab === 'comments'
                                    ? 'bg-indigo-700 text-white'
                                    : 'bg-gray-200/75'
                            "
                        >
                            Comments ({{ profile.comments_count }})
                        </Link>
                        <Link
                            href="?tab=groups"
                            class="px-3 py-2 rounded-lg"
                            :class="
                                tab === 'groups'
                                    ? 'bg-indigo-700 text-white'
                                    : 'bg-gray-200/75'
                            "
                        >
                            Groups ({{ profile.groups_count }})
                        </Link>
                    </div>
                    <div class="flex flex-col gap-3" v-if="tab === 'posts'">
                        <PostComponent
                            v-for="(post, i) in content.data"
                            :post="post"
                            :key="i"
                            :hasShadow="false"
                        />
                        <Pagination :pagination="content" :sticky="true" />
                    </div>
                    <div
                        class="flex flex-col gap-3"
                        v-else-if="tab === 'comments'"
                    >
                        <CommentComponent
                            v-for="(comment, i) in content.data"
                            :key="i"
                            :comment="comment"
                        />
                        <Pagination :pagination="content" :sticky="true" />
                    </div>
                    <div
                        class="flex flex-col gap-3"
                        v-else-if="tab === 'groups'"
                    >
                        <Link
                            v-for="(group, i) in content"
                            :key="i"
                            class="flex justify-between border border-solid border-gray-300 rounded-lg p-3 duration-300 hover:bg-indigo-700/10"
                        >
                            <p>{{ group.icon }} {{ group.name }}</p>
                            <p>{{ group.posts_count }} posts</p>
                        </Link>
                    </div>
                </div>
                <div class="flex flex-col items-center gap-2 mt-3" v-else>
                    <p class="text-3xl">This profile is private</p>
                    <p class="font-light">Follow to see content</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { UserOutlined } from "@ant-design/icons-vue";
import AppLayout from "../../Layouts/AppLayout.vue";
import ButtonComponent from "../../Components/ButtonComponent.vue";
import Pagination from "../../components/Pagination.vue";
import PostComponent from "../../Components/PostComponent.vue";
import CommentComponent from "../../Components/CommentComponent.vue";

const showEditModal = ref(false);

defineProps({
    profile: Object,
    content: Object,
    tab: String,
    hasAccess: Boolean,
    status: String,
});

const toggleEditProfileModal = () => {
    showEditModal.value = !showEditModal.value;
};
</script>
