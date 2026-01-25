import { NotificationItem } from "@/assets/customTypes";
import { getPeer } from "./registry";
import { getNotificationExclusions } from "@/utils/settings";
import { logError } from "@svkruik/sk-platform-formatters";
import { Pool, database } from "@svkruik/sk-platform-db-conn";

/**
 * Sends a notification to a peer via RTD.
 * If the user is offline they can fetch the database later to retrieve the notification.
 * @param notification The notification data to send to the peer.
 * @returns Status of the operation.
 */
export async function sendPeer(notification: NotificationItem): Promise<boolean> {
    try {
        const connection: Pool = await database("central");

        // Persist notification
        if (!getNotificationExclusions().includes(notification.data.type)) await connection.query("INSERT INTO user_notifications (id, object_id, object_type, type, data, source, url, is_silent) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", [
            notification.id,
            notification.object_id,
            notification.object_type,
            notification.type,
            notification.data,
            notification.source,
            notification.url,
            notification.is_silent,
        ]);

        // RTD
        getPeer(notification.object_id)?.send(JSON.stringify(notification));
        return true;
    } catch (error: any) {
        logError(error);
        return false;
    }
}
