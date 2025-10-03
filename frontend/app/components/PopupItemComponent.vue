<script lang="ts" setup>
import type { PopupItem } from "~/assets/customTypes";

// Props
const props = defineProps<{
    popupData: PopupItem;
}>();

// Reactive Data
const popupItem: Ref<HTMLButtonElement | null> = useTemplateRef<HTMLButtonElement>("popupItem");

// Methods

/**
 * Close an active popup.
 */
function closePopup() {
    const element: HTMLButtonElement | null = popupItem.value;
    if (!element) return;
    element.classList.remove("visible");
    setTimeout(() => {
        element.remove();
    }, 250);
}

// Lifecycle Hooks
onMounted(() => {
    // Fade In
    setTimeout(() => {
        if (popupItem.value) popupItem.value.classList.add("visible");
    }, 250);

    // Self-Destruct
    setTimeout(() => {
        closePopup();
    }, props.popupData.duration * 1000);
});
</script>

<template>
    <button ref="popupItem" class="popup shadow" title="Close Popup" type="button">
        <div class="left">
            <span :style="`background-color: var(--color-${popupData.type});`" class="color-indicator"></span>
            <p class="message">{{ popupData.message }}</p>
        </div>
        <div class="right">
            <div class="close-popup-button">
                <i class="fa-solid fa-xmark close-icon"></i>
            </div>
        </div>
        <span class="click-item" @click="closePopup()"></span>
    </button>
</template>

<style scoped>
.popup {
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-height: 50px;
    background-color: var(--color-fill);
    border-radius: var(--border-radius-low);
    cursor: pointer;
    opacity: 0;
    width: 100%;
    position: relative;
}

.visible {
    opacity: 1;
}

.left,
.right {
    display: flex;
    align-items: center;
    gap: 10px;
    height: 100%;
    text-align: left;
}

.color-indicator {
    height: -webkit-fill-available;
    width: 5px;
    border-top-left-radius: var(--border-radius-low);
    border-bottom-left-radius: var(--border-radius-low);
}

.message {
    max-width: 95%;
    margin: 5px 0;
}

.close-popup-button {
    background-color: transparent;
    height: 100%;
    width: 30px;
    opacity: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.popup:hover .close-popup-button {
    opacity: 1;
}

.close-icon {
    color: var(--color-text);
    cursor: pointer;
}
</style>