<script lang="ts">
import { defineComponent } from 'vue';
import type { PopupItem } from '@/assets/customTypes';
import PopupItemComponent from './PopupItemComponent.vue';

export default defineComponent({
    name: "PopupContainer",
    components: {
        PopupItemComponent
    },
    props: {
        pendingPopups: Array<PopupItem>
    }
});
</script>

<template>
    <section class="popup-container">
        <PopupItemComponent v-for="popup in pendingPopups" :id="popup.id" :popupData="popup"></PopupItemComponent>
    </section>
</template>

<style scoped>
.popup-container {
    display: flex;
    flex-direction: column;
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