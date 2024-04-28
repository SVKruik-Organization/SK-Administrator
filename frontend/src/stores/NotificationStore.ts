import { type NotificationItem, PromptTypes } from "@/assets/customTypes";
import { defineStore } from "pinia";
import { useStorage } from "@vueuse/core";

export const useNotificationStore = defineStore("NotificationStore", {
    state: () => {
        return {
            notifications: useStorage("notification", [] as Array<NotificationItem>)
        }
    },
    actions: {
        newNotification(notification: NotificationItem): void {
            this.notifications.unshift(notification);
        },
        markAsRead(ticket: string | undefined): void {
            if (!ticket) return;
            this.notifications.filter((notification: NotificationItem) => notification.ticket === ticket)[0].unread = false;
        },
        delete(ticket: string | undefined): void {
            if (!ticket) return;
            this.notifications.splice(this.notifications.indexOf(this.notifications.filter((notification: NotificationItem) => notification.ticket === ticket)[0]), 1);
        }
    },
    getters: {
        unreadNotifications(state): NotificationItem[] {
            return state.notifications.filter((notification: NotificationItem) => notification.unread);
        },
        highestPriority(state): string {
            if (state.notifications.filter((notification: NotificationItem) => notification.type === PromptTypes.danger).length) {
                return "danger";
            } else if (state.notifications.filter((notification: NotificationItem) => notification.type === PromptTypes.warning).length) {
                return "warning";
            } else if (state.notifications.filter((notification: NotificationItem) => notification.type === PromptTypes.success).length) {
                return "success";
            } else if (state.notifications.filter((notification: NotificationItem) => notification.type === PromptTypes.info).length) {
                return "info";
            } else return "text-light";
        },
        getByTicket(state) {
            return (ticket: string | undefined) => state.notifications.find((notification) => notification.ticket === ticket);
        },
    }
});