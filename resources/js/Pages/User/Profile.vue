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
                        <Link
                            href="/edit-profile"
                            class="my-3"
                            v-if="
                                profile.username === $page.props.user.username
                            "
                        >
                            <ButtonComponent> Edit profile </ButtonComponent>
                        </Link>
                        <ButtonComponent
                            class="my-3"
                            :class="
                                ['Following', 'Requested'].includes(status)
                                    ? 'bg-transparent border border-solid border-gray-300 !text-gray-400 hover:bg-gray-100/85'
                                    : ''
                            "
                            v-else
                            @click="handleFollowButton"
                        >
                            {{ status }}
                        </ButtonComponent>
                    </div>
                    <div
                        class="flex items-center divide-x [&>*]:px-7"
                        v-if="hasAccess"
                    >
                        <Link
                            :href="`/${profile.username}/followers?tab=followers`"
                            class="flex flex-col gap-2 items-center"
                        >
                            <p class="font-semibold">Followers</p>
                            <p class="font-light">
                                {{ profile.followed_by_count }}
                            </p>
                        </Link>
                        <Link
                            :href="`/${profile.username}/followers?tab=following`"
                            class="flex flex-col gap-2 items-center"
                        >
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
                    <div v-if="tab === 'posts'">
                        <div class="flex flex-col gap-3" v-if="contentSize > 0">
                            <PostComponent
                                v-for="(post, i) in content.data"
                                :post="post"
                                :key="i"
                                :hasShadow="false"
                            />
                            <Pagination :pagination="content" :shadow="false" />
                        </div>
                        <p v-else>This user has no posts</p>
                    </div>
                    <div v-else-if="tab === 'comments'">
                        <div class="flex flex-col gap-3" v-if="contentSize > 0">
                            <CommentComponent
                                v-for="(comment, i) in content.data"
                                :key="i"
                                :comment="comment"
                            />
                            <Pagination :pagination="content" :shadow="false" />
                        </div>
                        <p v-else>This user has no comments</p>
                    </div>
                    <div
                        class="flex flex-col gap-3"
                        v-else-if="tab === 'groups'"
                    >
                        <form
                            @submit.prevent="handleFormSubmit"
                            class="relative grid grid-cols-[1fr_6fr_2fr] gap-1"
                        >
                            <p
                                class="border border-solid border-gray-300 rounded-md p-2 outline-none focus:border-gray-500 cursor-pointer flex items-center justify-center text-xl leading-[0]"
                                @click="showPicker = !showPicker"
                            >
                                {{ createForm.icon }}
                            </p>
                            <input
                                v-model="createForm.name"
                                placeholder="Group name"
                                class="border border-solid border-gray-300 rounded-md p-2 outline-none focus:border-gray-500"
                            />
                            <EmojiPicker
                                :lazy="true"
                                class="picker absolute bottom-[115%]"
                                :native="true"
                                :disable-skin-tones="true"
                                :hide-group-names="true"
                                v-if="showPicker"
                                @select="handleIconPick"
                            />
                            <ButtonComponent> Create </ButtonComponent>
                            <p
                                class="col-span-full text-sm text-red-600"
                                v-if="createForm.errors.validation"
                            >
                                {{ createForm.errors.validation }}
                            </p>
                        </form>
                        <div
                            v-for="(group, i) in content"
                            :key="i"
                            class="grid grid-cols-[1fr_auto] items-center border border-solid border-gray-300 rounded-lg p-3 duration-300 hover:bg-indigo-700/10"
                            :class="
                                group.owner.username ===
                                    $page.props.user.username &&
                                group.name !== 'General'
                                    ? 'gap-4'
                                    : ''
                            "
                        >
                            <Link
                                :href="`/groups/${group.id}`"
                                class="flex justify-between"
                                :class="
                                    group.owner.username ===
                                        $page.props.user.username &&
                                    group.name !== 'General'
                                        ? 'pr-4 border-r border-solid border-r-gray-300'
                                        : ''
                                "
                            >
                                <p>{{ group.icon }} {{ group.name }}</p>
                                <p>{{ group.posts_count }} posts</p>
                            </Link>
                            <Link
                                :href="`/groups/${group.id}/edit`"
                                class="text-lg leading-[0] text-indigo-700 cursor-pointer"
                                v-if="
                                    group.owner.username ===
                                        $page.props.user.username &&
                                    group.name !== 'General'
                                "
                            >
                                <EditOutlined />
                            </Link>
                        </div>
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
import { Link, router, useForm } from "@inertiajs/vue3";
import { EditOutlined, UserOutlined } from "@ant-design/icons-vue";
import AppLayout from "../../Layouts/AppLayout.vue";
import ButtonComponent from "../../Components/ButtonComponent.vue";
import Pagination from "../../components/Pagination.vue";
import PostComponent from "../../Components/PostComponent.vue";
import CommentComponent from "../../Components/CommentComponent.vue";
import EmojiPicker from "vue3-emoji-picker";
import "vue3-emoji-picker/css";

const props = defineProps({
    profile: Object,
    content: Object,
    tab: String,
    hasAccess: Boolean,
    status: String,
});

const contentSize = ref(Object.keys(props.content?.data || {}).length);

const showPicker = ref(false);

const createForm = useForm({
    icon: "💾",
    name: "",
});

const handleFollowButton = () => {
    const options = ref({});

    if (props.status === "following" && props.profile.isPrivate) {
        options.value = {
            onBefore: () =>
                confirm(
                    "This profile is private. If you proceed, you'll lose access to it's content. Are you sure to unfollow?"
                ),
        };
    }

    router.post(`/follow/${props.profile.username}`, options.value);
};

const handleIconPick = (e) => {
    showPicker.value = false;
    createForm.icon = e["i"];
};

const handleFormSubmit = () => {
    createForm.post("/groups/create", {
        onSuccess: () => {
            createForm.reset();
        },
    });
};
</script>
