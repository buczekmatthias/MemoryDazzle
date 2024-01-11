<template>
    <EmojiPicker
        :lazy="true"
        class="absolute top-full"
        :native="true"
        :disable-skin-tones="true"
        :hide-group-names="true"
        @select="handleReactionPick"
    />
</template>

<script setup>
import EmojiPicker from "vue3-emoji-picker";
import "vue3-emoji-picker/css";

const emit = defineEmits(["reactionPick"]);

const handleReactionPick = (e) => {
    emit("reactionPick", { reaction: e.i, reaction_name: `:${e.n[1]}:` });
};
</script>
