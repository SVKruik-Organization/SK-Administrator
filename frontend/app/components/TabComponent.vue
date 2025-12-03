<script lang="ts" setup>
import type { TabComponentItem } from "@/assets/customTypes";

// Props
defineProps<{
    title: string;
    baseRoute: string;
    tabs: TabComponentItem[];
}>();
</script>

<template>
    <div class="tab-container-wrapper flex-col">
        <h1>{{ title }}</h1>
        <section class="tab-container flex">
            <NuxtLink v-for="tab in tabs" :key="tab.title" :to="`${baseRoute}${tab.route}`" class="tab-item flex-col">
                <p class="tab-title">{{ tab.title }}</p>
                <span class="router-link-exact-active active-indicator"></span>
            </NuxtLink>
        </section>
    </div>
</template>

<style scoped>
.tab-container-wrapper,
.tab-container {
    gap: 30px;
    width: fit-content;
}

.tab-item {
    gap: 20px;
    align-items: center;
}

.active-indicator {
    width: 100%;
    height: 2px;
    background-color: transparent;
    margin-bottom: -2px;
}

.router-link-exact-active span {
    background-color: var(--color-text-header);
}
</style>
