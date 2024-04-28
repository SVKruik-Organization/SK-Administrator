<script lang="ts">
import { type NotificationItem, type PopupItem, PromptTypes, UIThemes } from '@/assets/customTypes';
import { defineComponent } from 'vue';
import { useNotificationStore } from '@/stores/NotificationStore';
import { createTicket } from '@/utils/ticket';
import { useThemeStore } from '@/stores/ThemeStore';

export default defineComponent({
    name: "DashboardPage",
    emits: ["popup"],
    setup() {
        return {
            notificationStore: useNotificationStore(),
            themeStore: useThemeStore()
        }
    },
    methods: {
        /**
         * Temporary test function.
         */
        showPopupItem(): void {
            this.$emit("popup", {
                "id": createTicket(),
                "type": PromptTypes.success,
                "message": "This is a test message.",
                "expiryMilliseconds": 3000
            } as PopupItem);
        },
        showNotificationItem(): void {
            this.notificationStore.newNotification({
                "ticket": createTicket(),
                "type": PromptTypes.danger,
                "message": "This is a test notification.",
                "unread": true,
                "source": "System",
                "date": new Date()
            } as NotificationItem);
        },
        themeSwitch(theme: keyof typeof UIThemes): void {
            this.themeStore.setTheme(theme);
            this.$emit("popup", {
                "id": createTicket(),
                "type": PromptTypes.info,
                "message": `New theme active: ${theme}`,
                "expiryMilliseconds": 3000
            } as PopupItem);
        }
    }
});
</script>

<template>
    <section class="flex-col">
        <p>Dashboard Page</p>
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
        <RouterView></RouterView>
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
