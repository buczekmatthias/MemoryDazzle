<template>
    <div class="flex flex-wrap gap-1 p-3">
        <p
            class="reaction bg-slate-100 border border-slate-200 hover:bg-slate-200"
        >
            <SmileOutlined />
            <PlusOutlined class="text-[10px] leading-[0]" />
        </p>
        <p
            class="reaction"
            :class="
                group.user_reacted
                    ? 'bg-red-600/10 border-red-600 hover:bg-red-600/20 text-red-600 font-semibold'
                    : 'bg-slate-100 border-slate-200 hover:bg-slate-200'
            "
            v-for="group in reactions"
            :key="group.reaction_name"
            :title="group.reaction_name"
        >
            <span>{{ group.reaction }}</span>
            <span>{{ group.reaction_count }}</span>
        </p>
    </div>
</template>

<script setup>
import { SmileOutlined, PlusOutlined } from "@ant-design/icons-vue";

defineProps({
    reactions: Object,
});
</script>

<style lang="postcss">
.reaction {
    @apply flex items-center justify-center gap-1 py-1 px-2 duration-200 border-solid cursor-pointer rounded-lg border;
}
</style>
