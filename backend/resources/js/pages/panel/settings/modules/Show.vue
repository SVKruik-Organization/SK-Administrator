<script lang="ts" setup>
import PanelLayout from '@/layouts/PanelLayout.vue';
import type { Module } from '@/types/custom';

defineOptions({
    layout: PanelLayout,
});

const props = defineProps<{
    module: Module;
}>();
</script>

<template>
    <h1>Module Details</h1>
</template>