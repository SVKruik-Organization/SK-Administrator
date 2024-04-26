<script lang="ts">
import type { NotificationItem, PopupItem } from '@/assets/customTypes';
import { PromptTypes, UIThemes } from '@/assets/customTypes';
import { defineComponent } from 'vue';
import { useNotificationStore } from '@/stores/NotificationStore';
import { createTicket } from '@/utils/ticket';

export default defineComponent({
    name: "DashboardPage",
    emits: ["popup"],
    setup() {
        return {
            notificationStore: useNotificationStore()
        }
    },
    methods: {
        /**
         * Temporary test function.
         */
        showPopupItem(): void {
            const payload: PopupItem = {
                "id": createTicket(),
                "type": PromptTypes.success,
                "message": "This is a test message.",
                "expiryMilliseconds": 3000
            }
            this.$emit("popup", payload);
        },
        showNotificationItem(): void {
            const payload: NotificationItem = {
                "ticket": createTicket(),
                "type": PromptTypes.danger,
                "message": "This is a test notification.",
                "unread": true,
                "source": "System",
                "date": new Date()
            }
            this.notificationStore.newNotification(payload);
        },
        themeSwitch(theme: string): void {
            document.documentElement.className = "";
            if (theme === "reset") return;
            document.documentElement.classList.add(UIThemes[theme as keyof typeof UIThemes]);
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
                <button title="Theme Switch" type="button" @click="themeSwitch('tempA')">Theme A</button>
                <button title="Theme Switch" type="button" @click="themeSwitch('tempB')">Theme B</button>
                <button title="Theme Switch" type="button" @click="themeSwitch('tempC')">Theme C</button>
                <button title="Theme Switch" type="button" @click="themeSwitch('reset')">Reset</button>
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
