<script lang="ts" setup>
import { type NotificationItem, NotificationTypes, type PopupItem, PromptTypes, UIThemes } from "@/assets/customTypes";
import { useNotificationStore } from "#imports";
import { createTicket } from "@/utils/ticket";
import { useThemeStore } from "#imports";
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
        "duration": 3
    } as PopupItem);
}

/**
 * Temporary test function.
 */
function showNotificationItem(level: PromptTypes): void {
    notificationStore.newNotification({
        "user_id": userStore.user?.id,
        "type": NotificationTypes.acknowledge,
        "level": level,
        "data": {
            "message": "This is a test notification.",
            "details": "This is a longer description of the notification.",
        },
        "source": "Dashboard Page",
        "url": "/",
        "is_read": false,
        "is_silent": false,
        "ticket": createTicket(),
        "date_expiry": new Date(Date.now() + 1000 * 60),
        "date_creation": new Date(),
    } as NotificationItem);
}

/**
 * Temporary test function.
 * @param theme The theme to switch to.
 */
function themeSwitch(theme: UIThemes): void {
    themeStore.setTheme(theme);
    $event("popup", {
        "id": createTicket(),
        "type": PromptTypes.info,
        "message": `New theme active: ${theme}`,
        "duration": 3
    } as PopupItem);
}
</script>

<template>
    <section class="flex-col content-view">
        <h1>Dashboard</h1>
        <div class="temp flex-col">
            <strong>Functionality Testing:</strong>
            <button title="New Popup" type="button" @click="showPopupItem()">New Popup (success, 3 seconds)</button>
            <button title="New Notification" type="button" @click="showNotificationItem(PromptTypes.info)">New
                Notification (unread, info)
            </button>
            <button title="New Notification" type="button" @click="showNotificationItem(PromptTypes.success)">New
                Notification (unread, success)
            </button>
            <button title="New Notification" type="button" @click="showNotificationItem(PromptTypes.warning)">New
                Notification (unread, warning)
            </button>
            <button title="New Notification" type="button" @click="showNotificationItem(PromptTypes.danger)">New
                Notification (unread, danger)
            </button>
            <div class="flex">
                <button title="Theme Switch" type="button" @click="themeSwitch(UIThemes.default)">Default</button>
                <button title="Theme Switch" type="button" @click="themeSwitch(UIThemes.carbon)">Carbon</button>
                <button title="Theme Switch" type="button" @click="themeSwitch(UIThemes.monokai)">Monokai</button>
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
