<template>
    <div class="flex flex-wrap gap-1 p-3 relative">
        <p
            class="w-full text-indigo-700 text-end cursor-pointer"
            v-if="reactions.length > 0"
            @click="showReactionsPreview = true"
        >
            View reactions
        </p>
        <ReactionsPreview
            v-if="showReactionsPreview"
            :reactions="reactions"
            @closePreview="showReactionsPreview = false"
        />
        <p
            class="reaction bg-slate-100 border border-slate-200 hover:bg-slate-200"
            @click="showPicker = !showPicker"
        >
            <SmileOutlined />
            <PlusOutlined class="text-[10px] leading-[0]" />
        </p>
        <ReactionPicker @reactionPick="handlePickReaction" v-if="showPicker" />
        <p
            class="reaction"
            :class="
                group.user_reacted
                    ? 'bg-red-600/10 border-red-600 hover:bg-red-600/20 text-red-600 font-semibold'
                    : 'bg-slate-100 border-slate-200 hover:bg-slate-200'
            "
            v-for="(group, i) in reactions"
            :key="group.reaction_name"
            :title="group.reaction_name"
            @click="handleReactionClick(i)"
        >
            <span>{{ group.reaction }}</span>
            <span>{{ group.reaction_count }}</span>
        </p>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { SmileOutlined, PlusOutlined } from "@ant-design/icons-vue";
import ReactionPicker from "./ReactionPicker.vue";
import ReactionsPreview from "./ReactionsPreview.vue";

const props = defineProps({
    reactions: Object,
    post_id: String,
});

const showPicker = ref(false);
const showReactionsPreview = ref(false);

const handleAddReaction = (index) => {
    const entry = props.reactions[index];

    const addForm = useForm({
        reaction: entry.reaction,
        reaction_name: entry.reaction_name,
        post_id: props.post_id,
    });

    addForm.post(`/reactions/add`, {
        preserveScroll: true,
    });
};

const handleRemoveReaction = (index) => {
    router.delete(
        `/reactions/${props.post_id}/${props.reactions[index].reaction_name}/remove`,
        {
            preserveScroll: true,
        }
    );
};

const handleReactionClick = (index) => {
    if (props.reactions[index].user_reacted) {
        handleRemoveReaction(index);
    } else {
        handleAddReaction(index);
    }
};

const handlePickReaction = (reaction) => {
    showPicker.value = false;

    const matchingEntry = props.reactions.filter(
        (entry) => entry["reaction_name"] === reaction.reaction_name
    );

    if (matchingEntry.length > 0) {
        if (!matchingEntry.user_reacted) {
            const index = props.reactions.findIndex(
                (r) => r.reaction_name === reaction.reaction_name
            );
            handleAddReaction(index);
        }
    } else {
        const newReactionForm = useForm({
            reaction: reaction.reaction,
            reaction_name: reaction.reaction_name,
            post_id: props.post_id,
        });

        newReactionForm.post(`/reactions/add`, {
            preserveScroll: true,
        });
    }
};
</script>

<style lang="postcss">
.reaction {
    @apply flex items-center justify-center gap-1 py-1 px-2 duration-200 border-solid cursor-pointer rounded-lg border;
}
</style>
