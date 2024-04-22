<script lang="ts">
import type { PopupPayload } from '@/assets/customTypes';
import { PopupTypes } from '@/assets/customTypes';
import { defineComponent } from 'vue';

export default defineComponent({
    name: "DashboardPage",
    emits: ["popup"],
    methods: {
        /**
         * Temporary test function.
         */
        showPopupItem(): void {
            const payload: PopupPayload = {
                "type": PopupTypes.success,
                "message": "This is a test message.",
                "time": 60000
            }
            this.$emit("popup", payload);
        }
    }
});
</script>

<template>
    <section>
        <p>Dashboard Page</p>
        <button type="button" @click="showPopupItem()">Popup</button>
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
