<script lang="ts" setup>
import { useNotificationStore } from "~/stores/NotificationStore";
import type { NotificationItem } from "~/assets/customTypes";
const notificationStore = useNotificationStore();
const { $event } = useNuxtApp();

// Props
const props = defineProps<{
    message: NotificationItem;
}>();

// Methods

/**
 * Marks the notification as read. Removes the unread indicator.
 */
function markAsRead(): void {
    if (props.message.is_read) return;
    notificationStore.markAsRead(props.message.ticket);
}

/**
 * Deletes the notification.
 */
function deleteNotification(): void {
    notificationStore.delete(props.message.ticket);
}

/**
 * Navigates to the notification details page.
 */
function openDetails(): void {
    markAsRead();
    $event("close-navbar");
    navigateTo({
        path: "/panel/notifications",
        query: { "ticket": props.message.ticket },
    });
}
</script>

<template>
    <div :style="`background-color: var(--color-${message.is_read ? 'fill' : 'fill-dark'});`"
        class="flex-between notification-item" :class="{ 'notification-item-read': message.is_read }">
        <button class="flex notification-item-left" @click="openDetails()" type="button">
            <span :style="`background-color: var(--color-${message.level});`" class="type-indicator"></span>
            <p class="ellipsis">{{ typeof message.data === 'object' ? message.data?.message : message.data }}</p>
        </button>
        <section class="flex notification-item-right">
            <button v-if="!message.is_read" class="notification-action-button notification-action-button-read"
                title="Mark as Read" type="button" @click="markAsRead()">
                <i class="fa-regular fa-envelope-open"></i>
            </button>
            <button class="notification-action-button" :class="{ 'notification-action-button-read': !message.is_read }"
                title="Delete Notification" type="button" @click="deleteNotification()">
                <i class="fa-regular fa-trash-can"></i>
            </button>
        </section>
    </div>
</template>

<style scoped>
.notification-item {
    width: 100%;
    height: 30px;
    background-color: var(--color-fill-dark);
    border-radius: var(--border-radius-low);
    position: relative;
    gap: 0;
}

.notification-item-left {
    height: 100%;
    flex: 1;
    min-width: 0;
    gap: 0;
}

.notification-item-left p {
    height: 100%;
    box-sizing: border-box;
    padding: 4.5px;
    width: 100%;
    text-align: left;
    border: 1px solid transparent;
    margin-left: -1px;
}

.notification-item-read p {
    border-color: var(--color-border-dark) transparent;
}

.type-indicator {
    height: 100%;
    width: 5px;
    border-top-left-radius: var(--border-radius-low);
    border-bottom-left-radius: var(--border-radius-low);
}

.notification-item-right {
    gap: 5px;
    border: 1px solid transparent;
    box-sizing: border-box;
    padding: 4.5px;
    height: 30px;
    margin-left: -1px;
    border-top-right-radius: var(--border-radius-low);
    border-bottom-right-radius: var(--border-radius-low);
}

.notification-item-read .notification-item-right {
    border-color: var(--color-border-dark);
    border-left: transparent;
}

.notification-item-right button {
    opacity: 0;
}

.notification-item:hover button {
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

.notification-action-button-read {
    background-color: var(--color-fill);
}
</style>