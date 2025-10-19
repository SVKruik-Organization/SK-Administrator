import { NotificationItem } from "~/assets/customTypes";
import { getPeer } from "./registry";
import type { Pool } from "mariadb";
import { getNotificationExclusions } from "~/utils/settings";

/**
 * Sends a notification to a peer via RTD.
 * If the user is offline they can fetch the database later to retrieve the notification.
 * @param data The notification data to send to the peer.
 * @returns Status of the operation.
 */
export async function sendPeer(data: NotificationItem): Promise<boolean> {
    try {
        const connection: Pool = await database("central");

        // Persist notification
        if (!getNotificationExclusions().includes(data.type)) await connection.query("INSERT INTO user_notification (user_id, type, data, source, url, ticket, date_expiry, date_creation) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", [
            data.user_id,
            data.type,
            data.data,
            data.source,
            data.url,
            data.ticket,
            data.date_expiry,
            data.date_creation
        ]);

        // RTD
        getPeer(data.user_id)?.send(JSON.stringify(data));
        await connection.end();
        return true;
    } catch (error: any) {
        logError(error);
        return false;
    }
}
