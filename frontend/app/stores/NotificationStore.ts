import { type NotificationItem, PromptType } from "@/assets/customTypes";
import { defineStore } from "pinia";

export const useNotificationStore = defineStore("NotificationStore", {
    state: () => {
        return {
            notifications: [] as Array<NotificationItem>
        }
    },
    persist: {
        storage: piniaPluginPersistedstate.localStorage(),
    },
    actions: {
        newNotification(notification: NotificationItem): void {
            this.notifications.unshift(notification);
        },
        markAsRead(id: string | undefined): void {
            if (!id) return;
            const notification = this.notifications.filter((notification: NotificationItem) => notification.id === id)[0];
            if (notification) notification.deleted_at = new Date();
        },
        delete(id: string | undefined): void {
            if (!id) return;
            const notification = this.notifications.filter((notification: NotificationItem) => notification.id === id)[0];
            if (notification) notification.deleted_at = new Date();
        },
        clear(): void {
            this.notifications = [];
        }
    },
    getters: {
        unreadNotifications(state): NotificationItem[] {
            return state.notifications.filter((notification: NotificationItem) => notification.deleted_at === null);
        },
        highestPriority(state): string {
            if (state.notifications.filter((notification: NotificationItem) => notification.type === PromptType.danger).length) {
                return "danger";
            } else if (state.notifications.filter((notification: NotificationItem) => notification.type === PromptType.warning).length) {
                return "warning";
            } else if (state.notifications.filter((notification: NotificationItem) => notification.type === PromptType.success).length) {
                return "success";
            } else if (state.notifications.filter((notification: NotificationItem) => notification.type === PromptType.info).length) {
                return "info";
            } else return "text-light";
        },
        getNotificationById(state) {
            return (id: string | undefined) => state.notifications.find((notification) => notification.id === id);
        },
    }
});