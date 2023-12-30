<template>
    <div
        class="flex flex-col gap-4 items-center justify-center min-h-screen p-3 bg-slate-200"
    >
        <p class="text-xl font-semibold">MemoryDazzle</p>
        <div
            class="flex flex-col w-full max-w-lg shadow-md rounded-xl bg-white"
        >
            <div class="grid grid-cols-2">
                <Link
                    :href="$page.props.login_route"
                    class="securityRoute rounded-tl-xl"
                    :class="
                        $page.props.current_route === $page.props.login_route
                            ? 'activeSecurityRoute'
                            : ''
                    "
                    >Login</Link
                >
                <Link
                    :href="$page.props.register_route"
                    class="securityRoute rounded-tr-xl"
                    :class="
                        $page.props.current_route === $page.props.register_route
                            ? 'activeSecurityRoute'
                            : ''
                    "
                    >Register</Link
                >
            </div>
            <slot />
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<style lang="postcss" scoped>
.securityRoute {
    @apply text-sm p-3 bg-gray-300 flex items-center justify-center font-light duration-300 hover:bg-gray-200;
}
.activeSecurityRoute {
    @apply font-semibold text-lg pointer-events-none bg-indigo-700 text-white;
}
</style>
