<script lang="ts">
import type { NotificationItem, PopupItem } from '@/assets/customTypes';
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
                "type": PromptTypes.success,
                "message": "This is a test notification.",
                "unread": true,
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
        <div class="temp">
            <strong>Functionality Testing:</strong>
            <button title="New Popup" type="button" @click="showPopupItem()">New Popup (success, 3 seconds)</button>
            <button title="New Notification" type="button" @click="showNotificationItem()">New Notification (unread,
                success)</button>
        </div>
    </section>
</template>

<style scoped>
section,
.temp {
    display: flex;
    flex-direction: column;
    align-items: baseline;
    gap: 20px;
}

.temp {
    gap: 5px;
}

.temp button {
    background-color: var(--color-fill-dark);
    border-radius: var(--border-radius-low);
    box-sizing: border-box;
    padding: 5px;
}
</style>
