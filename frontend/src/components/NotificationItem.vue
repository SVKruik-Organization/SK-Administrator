<script lang="ts">
import { defineComponent } from 'vue';
import { useNotificationStore } from '@/stores/NotificationStore';

export default defineComponent({
    name: "NotificationItem",
    props: {
        "ticket": { type: String, required: true },
        "type": { type: String, required: true },
        "message": { type: String, required: true },
        "unread": { type: Boolean, required: true },
        "source": { type: String, required: true },
        "date": { type: Date, required: true },
    },
    setup() {
        return {
            notificationStore: useNotificationStore()
        }
    },
    methods: {
        markAsRead(): void {
            if (!this.unread) return;
            this.notificationStore.markAsRead(this.ticket);
        },
        deleteNotification(): void {
            this.notificationStore.delete(this.ticket);
        },
        openDetails(): void {
            this.$router.push(`/panel/dashboard/notifications?ticket=${this.ticket}`);
        }
    }
});
</script>

<template>
    <div :style="`background-color: var(--color-${unread ? 'fill-dark' : 'fill'});`" class="notification-item">
        <section class="notification-item-left">
            <span :style="`background-color: var(--color-${type});`" class="type-indicator"></span>
            <p class="ellipsis">{{ message }}</p>
            <button class="click-item" title="Open Details" type="button" @click="openDetails()"></button>
        </section>
        <section class="notification-item-right">
            <button v-if="unread" :style="`background-color: var(--color-${unread ? 'fill' : 'fill-dark'});`"
                    class="notification-action-button" title="Mark as Read" type="button" @click="markAsRead()">
                <i class="fa-regular fa-envelope-circle-check"></i>
                <span class="click-item"></span>
            </button>
            <button :style="`background-color: var(--color-${unread ? 'fill' : 'fill-dark'});`"
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