import { NotificationItem, NotificationTypes, PromptType } from "@/assets/customTypes";
import type { Peer } from "crossws";
import { registerPeer } from "./registry";
import { sendPeer } from "./out";
import { randomUUID } from "crypto";

/**
 * Handles incoming WebSocket messages for the RTD service.
 * @param notification The notification data received from the WebSocket.
 * @param peer The RTD connection peer that sent the message.
 * @returns A promise that resolves to a boolean indicating success or failure.
 */
export async function handleWebSocketMessage(notification: NotificationItem, peer: Peer): Promise<boolean> {
    switch (notification.data.type) {
        case NotificationTypes.initialize:
            registerPeer(notification.object_id, peer);
            break;
        default:
            return false;
    }

    return await sendPeer({
        id: randomUUID(),
        object_id: notification.object_id,
        object_type: notification.object_type,
        type: PromptType.info,
        data: {
            message: "Connection established.",
            type: NotificationTypes.acknowledge,
        },
        source: "RTD - Server",
        url: "/",
        is_silent: true,
        created_at: new Date(),
        updated_at: null,
        deleted_at: null,
    } as NotificationItem);
}
