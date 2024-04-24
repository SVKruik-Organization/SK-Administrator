<script lang="ts">
import { defineComponent } from 'vue';
import { useNotificationStore } from '@/stores/NotificationStore';

export default defineComponent({
    name: "NotificationItem",
    props: {
        "ticket": String,
        "type": String,
        "message": String,
        "unread": Boolean,
        "source": String,
        "date": Date
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
        }
    }
});
</script>

<template>
    <!-- TODO: #15 -->
    <div :style="`background-color: var(--color-${unread ? 'fill-dark' : 'fill'});`" class="notification-item">
        <section class="notification-item-left">
            <span :style="`background-color: var(--color-${type});`" class="type-indicator"></span>
            <p>{{ message }}</p>
        </section>
        <section class="notification-item-right">

        </section>
        <button class="click-item notification-click-item" title="Mark as Read" type="button"
                @click="markAsRead()"></button>
    </div>
</template>

<style scoped>
.notification-item {
    display: flex;
    align-items: center;
    gap: 10px;
    width: fit-content;
    min-width: 400px;
    height: 30px;
    border-top-right-radius: var(--border-radius-low);
    border-bottom-right-radius: var(--border-radius-low);
    background-color: var(--color-background);
    cursor: pointer;
    position: relative;
}

.notification-item-left {
    display: flex;
    align-items: center;
    gap: 10px;
    height: 100%;
    width: fit-content;
}

.type-indicator {
    display: block;
    height: 100%;
    width: 5px;
    border-top-left-radius: var(--border-radius-low);
    border-bottom-left-radius: var(--border-radius-low);
}

.click-item {
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 1;
}
</style>