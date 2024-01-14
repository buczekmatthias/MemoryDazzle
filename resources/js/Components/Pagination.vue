<template>
    <div
        class="flex flex-col gap-4 w-full bg-white rounded-lg p-3 font-light"
        :class="sticky ? 'sticky bottom-0' : 'shadow-md'"
    >
        <div class="flex items-center justify-center gap-1">
            <span>Showing</span>
            <span class="font-semibold">{{ pagination.from }}</span>
            <span>to</span>
            <span class="font-semibold">{{ pagination.to }}</span>
            <span>entries</span>
            <span>of total</span>
            <span class="font-semibold">{{ pagination.total }}</span>
        </div>
        <div
            class="flex items-center justify-center gap-4"
            v-if="pagination.last_page > 1"
        >
            <Link
                :href="pagination.first_page_url"
                class="py-1.5 px-4 rounded-md"
                :class="
                    pagination.current_page !== 1
                        ? 'bg-indigo-700 text-white'
                        : 'bg-slate-300 text-gray-400 pointer-events-none'
                "
                >First</Link
            >
            <div
                class="flex items-center gap-1 border border-solid border-gray-300 py-1.5 px-3 rounded-md"
            >
                <input
                    type="number"
                    @change="handlePageChange"
                    class="w-8 text-center bg-slate-200/85 outline-none"
                    :value="pagination.current_page"
                />
                <span class="">/ {{ pagination.last_page }}</span>
            </div>
            <Link
                :href="pagination.last_page_url"
                class="py-1.5 px-4 rounded-md"
                :class="
                    pagination.current_page !== pagination.last_page
                        ? 'bg-indigo-700 text-white'
                        : 'bg-slate-300 text-gray-400 pointer-events-none'
                "
                >Last</Link
            >
        </div>
    </div>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";

const props = defineProps({
    pagination: Object,
    sticky: {
        type: Boolean,
        default: false,
    },
});

const handlePageChange = (e) => {
    const choice = e.target.value;

    const page =
        choice < 1
            ? 1
            : choice > props.pagination.last_page
            ? props.pagination.last_page
            : choice;

    router.get(`?page=${page}`);
};
</script>
