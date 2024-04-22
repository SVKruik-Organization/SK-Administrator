<script lang="ts">
import { defineComponent } from 'vue';
import type { PopupItem } from '@/assets/customTypes';
export default defineComponent({
    name: "NotificationContainer",
    props: {
        pendingPopups: Array<PopupItem>
    },
    methods: {
        /**
         * Close a active popup.
         * @param {MouseEvent | string} input The ID of the popup element to delete.
         */
        closePopup(input: MouseEvent | string) {
            const id = typeof input === "string" ? input : (input.target as HTMLElement).id;
            const elements: HTMLCollectionOf<Element> = document.getElementsByClassName(`parent-${id}`);
            if (elements.length === 0) return;
            elements[0].classList.remove("visible");
            setTimeout(() => {
                if (elements[0]) elements[0].remove();
            }, 250);
        },
        /**
         * Softly show the notification, instead of instant display.
         * @param {string} id The ID of the popup element to fade-in.
         */
        fadeIn(id: number) {
            const elements: HTMLCollectionOf<Element> = document.getElementsByClassName(`parent-${id}`);
            if (elements.length === 0) return;
            elements[0].classList.add("visible");
        }
    }
});
</script>

<template>
    <section class="popup-container">
        <div @click="closePopup($event)" class="popup shadow" v-for="popup in pendingPopups"
            :class="`parent-${popup.id}`" :id="popup.id">
            <div class="left pointer" :id="popup.id">
                <span class="color-indicator pointer" :id="popup.id" :style="`background-color: ${popup.color}`"></span>
                <p class="message pointer" :id="popup.id">{{ popup.message }}</p>
            </div>
            <div class="right pointer" :id="popup.id">
                <button class="close-popup-button" :id="popup.id">
                    <i class="fa-solid fa-xmark close-icon" :id="popup.id"></i>
                </button>
            </div>
        </div>
    </section>
</template>

<style scoped>
.popup-container {
    width: 700px;
    position: absolute;
    left: 0;
    right: 0;
    bottom: 15px;
    margin-left: auto;
    margin-right: auto;
    z-index: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.popup {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 50px;
    background-color: var(--color-fill);
    border-radius: var(--border-radius-low);
    cursor: pointer;
    opacity: 0;
}

.visible {
    opacity: 1;
}

.left,
.right {
    height: 100%;
    display: flex;
    align-items: center;
    gap: 10px;
}

.color-indicator {
    height: 100%;
    width: 5px;
    border-top-left-radius: var(--border-radius-low);
    border-bottom-left-radius: var(--border-radius-low);
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