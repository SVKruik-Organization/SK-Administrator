import type { Peer, Message } from "crossws"
import { NotificationItem } from "@/assets/customTypes";
import { handleWebSocketMessage } from "../core/rtd/handlers";

export default defineWebSocketHandler({
    async message(peer: Peer, message: Message) {
        const notification: NotificationItem = JSON.parse(message.toString());
        if (!(notification satisfies NotificationItem)) return;
        await handleWebSocketMessage(notification, peer);
    },
});