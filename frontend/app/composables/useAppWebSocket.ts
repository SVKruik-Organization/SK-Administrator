import { useWebSocket } from "@vueuse/core";
import { useRuntimeConfig } from "#app";
import { NotificationTypes, PromptTypes, type NotificationItem } from "~/assets/customTypes";

let socketInstance: ReturnType<typeof useWebSocket> | null = null;

export const useAppWebSocket = () => {
    const config = useRuntimeConfig();

    if (!socketInstance) {
        const userStore = useUserStore();

        socketInstance = useWebSocket(config.public.wsUrl, {
            onConnected: () =>
                socketInstance?.send(JSON.stringify({
                    "user_id": userStore.user?.id,
                    "type": NotificationTypes.initialize,
                    "level": PromptTypes.info,
                    "data": {
                        "message": "Initializing connection.",
                    },
                    "source": "RTD - Client",
                    "url": "/",
                    "is_read": false,
                    "is_silent": true,
                    "ticket": createTicket(),
                    "date_expiry": new Date(Date.now() + 1000 * 60),
                    "date_creation": new Date(),
                } as NotificationItem)),
            autoReconnect: {
                retries: 3,
                delay: 2000,
            },
        });
    }

    return socketInstance;
};