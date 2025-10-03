// Website settings and configuration

import { NotificationTypes } from "~/assets/customTypes";

/**
 * Returns an array of notification types that should not be shown nor persisted.
 * This does not prohibit sending them to the client.
 * @returns An array of notification types that should be excluded.
 */
export function getNotificationExclusions(): Array<NotificationTypes> {
    return [
        NotificationTypes.initialize,
        NotificationTypes.acknowledge,
    ];
}