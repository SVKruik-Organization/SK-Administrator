import type { ProfileData } from "@/assets/customTypes";

/**
 * Handle the login callback from the auth provider.
 * Automatically creates a user session and retrieves the profile data.
 * @param token The token to validate.
 * @returns The status of the operation.
 * @throws An error if the request fails.
 */
export const useFetchValidateToken = async (token: string): Promise<ProfileData> => {
    try {
        const response: ProfileData = await $fetch("/api/auth/callback", {
            method: "POST",
            body: { token },
            headers: { "Content-Type": "application/json" },
        });
        await useUserSession().fetch();
        return response;
    } catch (error: any) {
        throw formatError(error);
    }
}