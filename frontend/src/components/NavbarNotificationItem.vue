<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    name: "NavbarNotificationItem",
    props: {
        "ticket": String,
        "type": String,
        "message": String,
        "read": Boolean,
        "source": String,
        "date": Date
    }
});
</script>

<template>
    <div class="notification-item">
        <span :style="`background-color: var(--color-${type});`" class="type-indicator"></span>
        <p>{{ message }}</p>
    </div>
</template>

<style scoped>
.notification-item {
    display: flex;
    align-items: center;
    gap: 10px;
    width: fit-content;
    height: 30px;
    border-top-right-radius: var(--border-radius-low);
    border-bottom-right-radius: var(--border-radius-low);
    min-width: 300px;
}

.type-indicator {
    height: 100%;
    width: 5px;
    border-top-left-radius: var(--border-radius-low);
    border-bottom-left-radius: var(--border-radius-low);
}
</style>