<script lang="ts" setup>
import { type NotificationItem, NotificationTypes, type PopupItem, PromptTypes, UIThemes } from "@/assets/customTypes";
import { useNotificationStore } from "@/stores/NotificationStore";
import { createTicket } from "@/utils/ticket";
import { useThemeStore } from "@/stores/ThemeStore";
const userStore = useUserStore();
const notificationStore = useNotificationStore();
const themeStore = useThemeStore();
const { $event } = useNuxtApp();

// Methods

/**
 * Temporary test function.
 */
function showPopupItem(): void {
    $event("popup", {
        "id": createTicket(),
        "type": PromptTypes.success,
        "message": "This is a test message.",
        "expiryMilliseconds": 3000
    } as PopupItem);
}

/**
 * Temporary test function.
 */
function showNotificationItem(): void {
    notificationStore.newNotification({
        "user_id": userStore.user.id,
        "type": NotificationTypes.acknowledge,
        "level": "danger",
        "data": {
            "message": "This is a test notification.",
            "details": "This is a longer description of the notification.",
        },
        "source": "Dashboard Page",
        "url": "/",
        "is_read": false,
        "ticket": createTicket(),
        "date_expiry": new Date(Date.now() + 1000 * 60),
        "date_creation": new Date(),
    } as NotificationItem);
}

/**
 * Temporary test function.
 * @param {keyof typeof UIThemes} theme The theme to switch to.
 */
function themeSwitch(theme: keyof typeof UIThemes): void {
    themeStore.setTheme(theme);
    $event("popup", {
        "id": createTicket(),
        "type": PromptTypes.info,
        "message": `New theme active: ${theme}`,
        "expiryMilliseconds": 3000
    } as PopupItem);
}
</script>

<template>
    <section class="flex-col content-view">
        <h1>Dashboard</h1>
        <div class="temp flex-col">
            <strong>Functionality Testing:</strong>
            <button title="New Popup" type="button" @click="showPopupItem()">New Popup (success, 3 seconds)</button>
            <button title="New Notification" type="button" @click="showNotificationItem()">New Notification (unread,
                danger)
            </button>
            <div class="flex">
                <button title="Theme Switch" type="button" @click="themeSwitch('Default')">Default</button>
                <button title="Theme Switch" type="button" @click="themeSwitch('Carbon')">Carbon</button>
                <button title="Theme Switch" type="button" @click="themeSwitch('Monokai')">Monokai</button>
            </div>
        </div>
        <NuxtPage></NuxtPage>
    </section>
</template>

<style scoped>
section {
    gap: 20px;
}

.temp {
    align-items: baseline;
}

.temp button {
    background-color: var(--color-fill-dark);
    border-radius: var(--border-radius-low);
    box-sizing: border-box;
    padding: 5px;
}
</style>
