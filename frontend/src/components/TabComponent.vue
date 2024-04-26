<script lang="ts">
import type { TabComponentItem } from '@/assets/customTypes';
import { defineComponent } from 'vue';

export default defineComponent({
    name: "TabComponent",
    props: {
        "title": { type: String, required: true },
        "baseRoute": { type: String, required: true },
        "tabs": { type: Array<TabComponentItem>, required: true }
    }
});
</script>

<template>
    <div class="tab-container-wrapper flex-col">
        <h1>{{ title }}</h1>
        <section class="tab-container flex">
            <RouterLink v-for="tab in tabs" :to="`${baseRoute}${tab.route}`" class="tab-item flex-col">
                <p class="tab-title">{{ tab.title }}</p>
                <span class="active-exact-router-link active-indicator"></span>
            </RouterLink>
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

.active-exact-router-link span {
    background-color: var(--color-text-header);
}
</style>
