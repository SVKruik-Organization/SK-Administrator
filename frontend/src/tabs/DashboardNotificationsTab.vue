<script lang="ts">
import { defineComponent } from 'vue';
import { useNotificationStore } from '@/stores/NotificationStore';

export default defineComponent({
    name: "DashboardNotificationsTab",
    setup() {
        return {
            notificationStore: useNotificationStore()
        }
    },
});
</script>

<template>
    <section>
        <p>Notifications Tab</p>
        <p>Active notification: {{
                notificationStore.getByTicket($route.query.ticket?.toString())
                || "Select a notification for details."
            }}
        </p>
    </section>
</template>

<style scoped></style>
