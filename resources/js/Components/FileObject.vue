<template>
    <div class="flex items-center gap-4 bg-slate-600 text-white rounded-lg p-4">
        <FileOutlined class="text-2xl leading-[0]" />
        <div>
            <p class="font-semibold text-lg">{{ file.path }}</p>
            <p class="text-xs">
                {{
                    file.extension.charAt(0).toUpperCase() +
                    file.extension.slice(1)
                }}
                file
            </p>
        </div>
        <a
            :href="`/download/${file.id}`"
            target="_blank"
            class="ml-auto text-2xl leading-[0]"
        >
            <DownloadOutlined />
        </a>
    </div>
</template>

<script setup>
import { FileOutlined, DownloadOutlined } from "@ant-design/icons-vue";

const props = defineProps({
    file: Object,
});
</script>
