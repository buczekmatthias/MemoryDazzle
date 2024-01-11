<template>
    <div class="flex flex-col min-h-screen md:flex-row">
        <AppNavigation />
        <slot />
    </div>
</template>

<script setup>
import AppNavigation from "../Components/AppNavigation.vue";
</script>
