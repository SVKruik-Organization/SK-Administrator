import type { UserData } from "~/assets/customTypes";
/**
 * Create a new session for a user via email.
 * @param email The email address of the user.
 * @param password The password of the email account.
 * @returns The response from the API.
 * @throws An error if the request fails.
 */
export const useFetchLoginEmail = async (email: string, password: string): Promise<UserData | true> => {
    try {
        const data = await $fetch("/api/auth/login/email", {
            method: "POST",
            body: {
                email,
                password,
            },
            headers: {
                "Content-Type": "application/json",
            },
        });
        if (typeof data === "boolean") return data;
        const { fetch: fetchSession } = useUserSession();
        await fetchSession();
        return data;
    } catch (error: any) {
        throw formatError(error);
    }
}