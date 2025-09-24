import type { UserData } from "~/assets/customTypes";

/**
 * Submits the 2FA pin for verification.
 * @param email The email of the user.
 * @param pin The 2FA pin to submit.
 * @returns The status of the operation.
 */
export const useFetchSubmit2FA = async (email: string, pin: string): Promise<UserData> => {
    try {
        const data = await $fetch("/api/auth/2fa", {
            method: "POST",
            body: {
                email,
                pin,
            },
            headers: {
                "Content-Type": "application/json",
            },
        });
        const { fetch: fetchSession } = useUserSession();
        await fetchSession();
        return data;
    } catch (error: any) {
        throw formatError(error);
    }
}