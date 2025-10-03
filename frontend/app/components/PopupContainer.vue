<script lang="ts" setup>
import type { PopupItem } from "~/assets/customTypes";
const { $listen } = useNuxtApp();

$listen("popup", (event: any) => {
    pendingPopups.value.push(event as PopupItem);
});

// Reactive Data
const pendingPopups = ref<Array<PopupItem>>([]);
</script>

<template>
    <section class="popup-container flex-col">
        <PopupItemComponent v-for="popup in pendingPopups" :id="popup.id" :key="popup.id" :popupData="popup">
        </PopupItemComponent>
    </section>
</template>

<style scoped>
.popup-container {
    gap: 10px;
    width: 95%;
    max-width: 700px;
    position: absolute;
    left: 0;
    right: 0;
    bottom: 15px;
    margin-left: auto;
    margin-right: auto;
    z-index: 4;
}
</style>