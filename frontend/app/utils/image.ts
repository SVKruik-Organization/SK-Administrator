import type { User } from "#auth-utils";
import { UserTypes } from "~/assets/customTypes";

/**
 * Get the image URL for a user or guest.
 * @param imageName The name of the image file.
 * @param userType The type of user (USER or GUEST).
 * @returns The URL of the image.
 */
export function getImageUrl(user: User | null): string {
    // TEMPORARY until CDN is set up
    return `/${user?.imageName}.png` || "";

    const backupImage = `/images/default.png`;
    if (!user) return backupImage;

    switch (user.type) {
        case UserTypes.USER:
            return `/images/users/${user.imageName}`;
        case UserTypes.GUEST:
            return `/images/guests/${user.imageName}`;
        default:
            return backupImage;
    }
}