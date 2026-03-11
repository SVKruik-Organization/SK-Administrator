<script lang="ts" setup>
import { Ref, computed, inject, onMounted } from 'vue';

const props = defineProps<{
    name: string;
    label?: string;
}>();

const registerTab = inject<(name: string, label: string) => void>('registerTab');
const activeTabName = inject<Ref<string | null> | null>('activeTabName', null);

onMounted(() => {
    if (registerTab) {
        registerTab(props.name, props.label ?? props.name);
    }
});

const isActive = computed(() => {
    if (!activeTabName) return true;
    return activeTabName.value === props.name;
});
</script>

<template>
    <div v-if="isActive" :id="name">
        <slot />
    </div>
</template>