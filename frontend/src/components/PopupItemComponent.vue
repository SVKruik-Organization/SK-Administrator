<script lang="ts">
import { defineComponent } from 'vue';
import type { PropType } from 'vue';
import type { PopupItem } from '@/assets/customTypes';

export default defineComponent({
    name: "PopupItemComponent",
    props: {
        "popupData": { type: Object as PropType<PopupItem>, required: true }
    },
    data() {
        return {
            "popupItem": null as unknown as HTMLButtonElement
        }
    },
    methods: {
        /**
         * Close an active popup.
         */
        closePopup() {
            const element: HTMLButtonElement = this.$refs["popupItem"] as HTMLButtonElement;
            if (!element) return;
            element.classList.remove("visible");
            setTimeout(() => {
                element.remove();
            }, 250);
        },
    },
    mounted() {
        this.popupItem = this.$refs["popupItem"] as HTMLButtonElement;

        // Fade In
        setTimeout(() => {
            this.popupItem.classList.add("visible");
        }, 250);

        // Self-Destruct
        setTimeout(() => {
            this.closePopup();
        }, this.popupData.expiryMilliseconds);
    }
});
</script>

<template>
    <button title="Close Popup" type="button" ref="popupItem" class="popup shadow">
        <div class="left">
            <span :style="`background-color: var(--color-${popupData.type});`" class="color-indicator"></span>
            <p class="message ellipsis">{{ popupData.message }}</p>
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
    height: 50px;
    background-color: var(--color-fill);
    border-radius: var(--border-radius-low);
    cursor: pointer;
    opacity: 0;
    width: 100%;
    position: relative;
}

.click-item {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: 1;
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