<template>
    <div
        class="fixed top-0 left-0 z-30 bg-gray-800/80 h-screen w-full flex flex-col gap-5 justify-end"
        id="reactions-preview"
    >
        <CloseOutlined
            @click="closePreview"
            class="self-center bg-white p-4 rounded-full text-2xl leading-[0]"
        />
        <div class="bg-white rounded-t-xl pb-2 min-h-[50%] max-h-[85%]">
            <div
                class="flex gap-2 p-3 border-b border-solid border-b-gray-300 overflow-auto"
            >
                <div
                    class="flex gap-2 p-1.5 rounded-lg"
                    v-for="(reaction, i) in reactions"
                    :key="reaction.reaction_name"
                    @click="handleGroupChange(i)"
                    :class="
                        index === i
                            ? 'bg-slate-500/25 cursor-none'
                            : 'duration-300 hover:bg-slate-500/10 cursor-pointer'
                    "
                >
                    <span>{{ reaction.reaction }}</span>
                    <span>{{ reaction.reaction_count }}</span>
                </div>
            </div>
            <div class="px-3 py-2 flex flex-col gap-4 h-full overflow-auto">
                <p class="sticky -top-2 py-2 px-1 bg-white text-gray-500">
                    {{ pickedReactions.reaction_name }}
                </p>
                <a
                    class="flex gap-2 items-center duration-300 hover:bg-slate-500/10 rounded-lg p-1"
                    :href="`/${user.username}`"
                    v-for="user in pickedReactions.users"
                    :key="user.username"
                    target="_blank"
                >
                    <img
                        :src="user.avatar"
                        alt="User avatar"
                        v-if="user.avatar"
                        class="h-12 w-12 object-cover rounded-lg"
                    />
                    <UserOutlined
                        class="bg-slate-200/75 rounded-full p-2"
                        v-else
                    />
                    <div class="flex gap-2 items-center">
                        <span
                            class="font-semibold leading-none"
                            v-if="user.displayname"
                        >
                            {{ user.displayname }}
                        </span>
                        <span
                            class="text-gray-400 font-light"
                            :class="!user.displayname ? 'text-base' : ''"
                        >
                            {{ user.username }}
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { UserOutlined, CloseOutlined } from "@ant-design/icons-vue";

const props = defineProps({
    reactions: Object,
});

const emit = defineEmits(["closePreview"]);

const index = ref(0);
const pickedReactions = ref(props.reactions[0]);

const closePreview = (e) => {
    emit("closePreview");
};

const handleGroupChange = (i) => {
    index.value = i;
    pickedReactions.value = props.reactions[i];
};
</script>

<style lang="postcss">
body:has(#reactions-preview) {
    @apply max-h-screen overflow-hidden;
}
</style>
