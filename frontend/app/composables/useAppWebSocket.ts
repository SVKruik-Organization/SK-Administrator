import { useWebSocket } from "@vueuse/core";
import { useRuntimeConfig } from "#app";
import { NotificationTypes, PromptType, UserTypes, type NotificationItem } from "@/assets/customTypes";

let socketInstance: ReturnType<typeof useWebSocket> | null = null;

export const useAppWebSocket = () => {
    const config = useRuntimeConfig();

    if (!socketInstance) {
        const userSession = useUserSession();

        socketInstance = useWebSocket(config.public.wsUrl, {
            onConnected: () =>
                socketInstance?.send(JSON.stringify({
                    "id": self.crypto.randomUUID(),
                    "object_id": userSession.user.value?.id,
                    "object_type": UserTypes.USER,
                    "type": PromptType.info,
                    "data": {
                        "message": "Initializing connection.",
                        "type": NotificationTypes.initialize,
                    },
                    "source": "RTD - Client",
                    "url": "/",
                    "is_silent": true,
                    "created_at": new Date(Date.now() + 1000 * 60),
                    "updated_at": null,
                    "deleted_at": null,
                } as NotificationItem)),
            autoReconnect: {
                retries: 3,
                delay: 2000,
            },
        });
    }

    return socketInstance;
};