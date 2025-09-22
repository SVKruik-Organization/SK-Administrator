<script lang="ts" setup>
import type { PopupItem } from "@/assets/customTypes";
import PopupItemComponent from "./PopupItemComponent.vue";
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
    width: 700px;
    position: absolute;
    left: 0;
    right: 0;
    bottom: 15px;
    margin-left: auto;
    margin-right: auto;
    z-index: 1;
}
</style>