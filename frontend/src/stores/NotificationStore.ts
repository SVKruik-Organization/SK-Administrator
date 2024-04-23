import type { NotificationItem } from "@/assets/customTypes";
import { defineStore } from "pinia";
import { useStorage } from "@vueuse/core";

export const useNotificationStore = defineStore("NotificationStore", {
    state: () => {
        return {
            notifications: useStorage('notification', [] as Array<NotificationItem>)
        }
    },
    actions: {
        newNotification(notification: NotificationItem): void {
            this.notifications.push(notification);
        }
    }
});