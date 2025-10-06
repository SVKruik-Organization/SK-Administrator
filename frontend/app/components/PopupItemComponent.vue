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
    <button ref="popupItem" class="popup shadow" title="Close Popup" type="button" @click="closePopup()">
        <span :style="`background-color: var(--color-${popupData.type});`" class="color-indicator"></span>
        <div class="left">
            <p class="message">{{ popupData.message }}</p>
        </div>
        <div class="right">
            <div class="close-popup-button">
                <i class="fa-solid fa-xmark close-icon"></i>
            </div>
        </div>
    </button>
</template>

<style scoped>
.popup {
    display: flex;
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
    text-align: left;
    height: 100%;
}

.left {
    flex: 1;
}

.color-indicator {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 5px;
    border-top-left-radius: var(--border-radius-low);
    border-bottom-left-radius: var(--border-radius-low);
}

.message {
    height: 100%;
    padding: 10px 15px;
    box-sizing: border-box;
}

.right {
    margin-left: auto;
}

.close-popup-button {
    background-color: transparent;
    height: 100%;
    width: 30px;
    opacity: 0;
    display: flex;
}

.popup:hover .close-popup-button {
    opacity: 1;
}

.close-icon {
    color: var(--color-text);
    cursor: pointer;
}
</style>