<script setup lang="ts">
import type { NotificationItem } from '@/types/custom';

const props = defineProps<{
    notification: NotificationItem;
}>();
</script>

<template>
    <div>
        <h1>Notification Item</h1>
    </div>
</template>