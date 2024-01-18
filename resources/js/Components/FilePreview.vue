<template>
    <div
        class="fixed top-0 left-0 z-30 bg-gray-800/80 h-screen w-full flex flex-col gap-4 py-4 items-center"
        id="file-preview"
    >
        <img
            v-if="file.type === 'image'"
            :src="file.filename"
            alt="Preview of image"
            class="preview-file"
        />
        <video
            v-else
            :src="file.filename"
            controls
            autoplay
            class="preview-file"
        ></video>
        <div class="flex gap-12">
            <CloseOutlined
                @click="closePreview"
                class="bg-white p-4 rounded-full text-2xl leading-[0]"
            />
            <a
                :href="`/download/${file.id}`"
                target="_blank"
                class="bg-emerald-600 text-white p-4 rounded-full text-2xl leading-[0]"
                v-if="withDownload"
            >
                <DownloadOutlined />
            </a>
        </div>
    </div>
</template>

<script setup>
import { CloseOutlined, DownloadOutlined } from "@ant-design/icons-vue";

const props = defineProps({
    file: Object,
    withDownload: { type: Boolean, default: true },
});

const emit = defineEmits(["closePreview"]);

const closePreview = (e) => {
    emit("closePreview");
};
</script>

<style lang="postcss">
body:has(#file-preview) {
    @apply max-h-screen overflow-hidden;
}

.preview-file {
    @apply max-h-[90%] max-w-[95%] my-auto;
}
</style>
