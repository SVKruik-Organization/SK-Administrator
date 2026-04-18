<script lang="ts" setup>
import { onMounted, onUnmounted, provide, ref } from 'vue';

type Tab = {
    name: string;
    label: string;
};

const tabs = ref<Tab[]>([]);
const activeTabName = ref<string | null>(null);

function registerTab(name: string, label: string): void {
    if (!tabs.value.find((tab) => tab.name === name)) {
        tabs.value.push({ name, label });

        if (!window.location.hash && tabs.value.length === 1 && activeTabName.value === null) {
            activeTabName.value = name;
        }
    }
}

function syncFromHash(): void {
    const hash = window.location.hash.replace('#', '');

    if (hash) {
        activeTabName.value = hash;
        return;
    }

    if (tabs.value.length > 0 && activeTabName.value === null) {
        activeTabName.value = tabs.value[0].name;
    }
}

function setActive(name: string): void {
    activeTabName.value = name;
    const url = new URL(window.location.href);
    url.hash = `#${name}`;
    window.history.replaceState({}, '', url);
}

provide('registerTab', registerTab);
provide('activeTabName', activeTabName);

onMounted(() => {
    syncFromHash();
    window.addEventListener('hashchange', syncFromHash);
});

onUnmounted(() => {
    window.removeEventListener('hashchange', syncFromHash);
});
</script>

<template>
    <div>
        <div class="flex gap-6 border-b-2 border-theme-dark mb-4">
            <button
                v-for="tab in tabs"
                :key="tab.name"
                type="button"
                class="pb-2 text-sm font-medium border-b-2 -mb-0.5 cursor-pointer"
                :class="
                    activeTabName === tab.name
                        ? 'border-gray-700 text-gray-700'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-400'
                "
                @click="setActive(tab.name)"
            >
                {{  tab.label }}
            </button>
        </div>

        <slot />
    </div>
</template>