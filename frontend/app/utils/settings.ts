// Website settings and configuration

import { NotificationTypes, UserTypes } from "@/assets/customTypes";

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

/** Get the session TTL (time to live) in seconds based on user type.
 * @param userType The type of user (USER or GUEST).
 * @returns The session TTL in seconds.
 */
export function getSessionTTL(userType: UserTypes): {
    maxAge: number;
} {
    const base = 60 * 60;
    return {
        maxAge: userType === UserTypes.GUEST ? base * 4 : base * 24 * 30, // 4 hours for guests, 30 days for users
    };
}