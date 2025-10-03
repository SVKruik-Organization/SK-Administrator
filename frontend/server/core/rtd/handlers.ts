import { NotificationItem, NotificationTypes, PromptTypes } from "~/assets/customTypes";
import type { Peer } from "crossws";
import { registerPeer } from "./registry";
import { sendPeer } from "./out";
import { createTicket } from "~/utils/ticket";

/**
 * Handles incoming WebSocket messages for the RTD service.
 * @param data The notification data received from the WebSocket.
 * @param peer The RTD connection peer that sent the message.
 * @returns A promise that resolves to a boolean indicating success or failure.
 */
export async function handleWebSocketMessage(data: NotificationItem, peer: Peer): Promise<boolean> {
    switch (data.type) {
        case NotificationTypes.initialize:
            registerPeer(data.user_id, peer);
            break;
        default:
            return false;
    }

    return await sendPeer({
        user_id: data.user_id,
        type: NotificationTypes.acknowledge,
        level: PromptTypes.info,
        data: {
            message: "Connection established.",
        },
        source: "RTD - Handlers",
        url: "/",
        is_read: false,
        is_silent: true,
        ticket: createTicket(),
        date_expiry: new Date(Date.now() + 1000 * 60 * 60 * 24),
        date_creation: new Date(),
    });
}
