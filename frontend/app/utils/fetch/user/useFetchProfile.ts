import type { ProfileData } from "@/assets/customTypes";

/**
 * Fetch user profile data.
 * @param userId The ID of the user.
 * @param profileId The ID of the profile.
 * @returns The user profile data.
 */
export const useFetchProfile = async (profileId: string | null): Promise<ProfileData> => {
    try {
        return await $fetch("/api/user/profile", {
            method: "PATCH",
            params: { profileId },
            headers: { "Content-Type": "application/json" },
        });
    } catch (error: any) {
        throw formatError(error);
    }
}