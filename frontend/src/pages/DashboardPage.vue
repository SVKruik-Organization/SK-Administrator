<script lang="ts">
import type { NotificationItem, PopupPayload } from '@/assets/customTypes';
import { PromptTypes } from '@/assets/customTypes';
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
            const payload: PopupPayload = {
                "type": PromptTypes.success,
                "message": "This is a test message.",
                "time": 60000
            }
            this.$emit("popup", payload);
        },
        showNotificationItem(): void {
            const payload: NotificationItem = {
                "ticket": createTicket(),
                "type": PromptTypes.success,
                "message": "This is a test notification.",
                "read": false,
                "source": "System",
                "date": new Date()
            }
            this.notificationStore.newNotification(payload);
        }
    }
});
</script>

<template>
    <section>
        <p>Dashboard Page</p>
        <button type="button" @click="showPopupItem()">Popup</button>
        <button type="button" @click="showNotificationItem()">Notification</button>
    </section>
</template>

<style scoped>
section {
    display: flex;
    flex-direction: column;
    align-items: baseline;
    gap: 10px;
}
</style>
