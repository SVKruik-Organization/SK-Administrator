import { type NotificationItem, PromptTypes } from "~/assets/customTypes";
import { defineStore } from "pinia";
import { useStorage } from "@vueuse/core";

export const useNotificationStore = defineStore("NotificationStore", {
    state: () => {
        return {
            notifications: useStorage("notification", [] as Array<NotificationItem>)
        }
    },
    persist: {
        storage: piniaPluginPersistedstate.localStorage(),
    },
    actions: {
        newNotification(notification: NotificationItem): void {
            this.notifications.unshift(notification);
        },
        markAsRead(ticket: string | undefined): void {
            if (!ticket) return;
            const notification = this.notifications.filter((notification: NotificationItem) => notification.ticket === ticket)[0];
            if (notification) notification.is_read = true;
        },
        delete(ticket: string | undefined): void {
            if (!ticket) return;
            const notification = this.notifications.filter((notification: NotificationItem) => notification.ticket === ticket)[0];
            if (notification) this.notifications.splice(this.notifications.indexOf(notification), 1);
        },
        clear(): void {
            this.notifications = [];
        }
    },
    getters: {
        unreadNotifications(state): NotificationItem[] {
            return state.notifications.filter((notification: NotificationItem) => notification.is_read === false);
        },
        highestPriority(state): string {
            if (state.notifications.filter((notification: NotificationItem) => notification.level === PromptTypes.danger).length) {
                return "danger";
            } else if (state.notifications.filter((notification: NotificationItem) => notification.level === PromptTypes.warning).length) {
                return "warning";
            } else if (state.notifications.filter((notification: NotificationItem) => notification.level === PromptTypes.success).length) {
                return "success";
            } else if (state.notifications.filter((notification: NotificationItem) => notification.level === PromptTypes.info).length) {
                return "info";
            } else return "text-light";
        },
        getByTicket(state) {
            return (ticket: string | undefined) => state.notifications.find((notification) => notification.ticket === ticket);
        },
    }
});