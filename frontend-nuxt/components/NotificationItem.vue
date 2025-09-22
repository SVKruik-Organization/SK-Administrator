<script lang="ts" setup>
import { useNotificationStore } from "@/stores/NotificationStore";
import type { NotificationItem } from "~/assets/customTypes";
const notificationStore = useNotificationStore();
const router = useRouter();

// Props
const props = defineProps<{
    message: NotificationItem;
}>();

// Methods
function markAsRead(): void {
    if (props.message.is_read) return;
    notificationStore.markAsRead(props.message.ticket);
}
function deleteNotification(): void {
    notificationStore.delete(props.message.ticket);
}
function openDetails(): void {
    router.push(`/panel/notifications?ticket=${props.message.ticket}`);
}
</script>

<template>
    <div :style="`background-color: var(--color-${message.is_read ? 'fill' : 'fill-dark'});`" class="notification-item">
        <section class="notification-item-left">
            <span :style="`background-color: var(--color-${message.level});`" class="type-indicator"></span>
            <p class="ellipsis">{{ typeof message.data === 'object' ? message.data.message : message.data }}</p>
            <button class="click-item" title="Open Details" type="button" @click="openDetails()"></button>
        </section>
        <section class="notification-item-right">
            <button v-if="!message.is_read"
                :style="`background-color: var(--color-${message.is_read ? 'fill' : 'fill-dark'});`"
                class="notification-action-button" title="Mark as Read" type="button" @click="markAsRead()">
                <i class="fa-regular fa-envelope-circle-check"></i>
                <span class="click-item"></span>
            </button>
            <button :style="`background-color: var(--color-${message.is_read ? 'fill' : 'fill-dark'});`"
                class="notification-action-button" title="Delete Notification" type="button"
                @click="deleteNotification()">
                <i class="fa-regular fa-envelope-open"></i>
                <span class="click-item"></span>
            </button>
        </section>
    </div>
</template>

<style scoped>
.notification-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    width: fit-content;
    min-width: 400px;
    height: 30px;
    border-top-right-radius: var(--border-radius-low);
    border-bottom-right-radius: var(--border-radius-low);
    background-color: var(--color-background);
    position: relative;
}

.notification-item-left {
    display: flex;
    align-items: center;
    gap: 10px;
    height: 100%;
    width: fit-content;
    position: relative;
}

.type-indicator {
    display: block;
    height: 100%;
    width: 5px;
    border-top-left-radius: var(--border-radius-low);
    border-bottom-left-radius: var(--border-radius-low);
}

.notification-item-right {
    display: flex;
    align-items: center;
    opacity: 0;
    gap: 5px;
    margin-right: 5px;
}

.notification-item:hover .notification-item-right {
    opacity: 1;
}

.notification-action-button {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--color-fill-dark);
    height: 20px;
    width: 20px;
    border-radius: var(--border-radius-low);
    position: relative;
    box-sizing: border-box;
    padding: 5px;
}

.notification-action-button i {
    font-size: small;
}
</style>