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
            this.notifications.unshift(notification);
        },
        markAsRead(ticket: string | undefined): void {
            if (!ticket) return;
            this.notifications.filter((notification: NotificationItem) => notification.ticket === ticket)[0].unread = false;
        }
    },
    getters: {
        unreadNotifications: (state) => state.notifications.filter((notification: NotificationItem) => notification.unread)
    }
});