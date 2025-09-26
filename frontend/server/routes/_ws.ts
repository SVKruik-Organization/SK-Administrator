import type { Peer, Message } from "crossws"
import { NotificationItem } from "~/assets/customTypes";
import { handleWebSocketMessage } from "../core/rtd/handlers";

export default defineWebSocketHandler({
    async message(peer: Peer, message: Message) {
        const data: NotificationItem = JSON.parse(message.toString());
        if (!(data satisfies NotificationItem)) return;
        await handleWebSocketMessage(data, peer);
    },
});