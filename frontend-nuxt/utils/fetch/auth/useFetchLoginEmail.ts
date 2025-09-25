/**
 * Create a new session for a user via email.
 * @param email The email address of the user.
 * @param password The password of the email account.
 */
export const useFetchLoginEmail = async (email: string, password: string): Promise<string> => {
    try {
        return await $fetch("/api/auth/login/email", {
            method: "POST",
            body: {
                email,
                password,
            },
            headers: {
                "Content-Type": "application/json",
            },
        });
    } catch (error: any) {
        throw formatError(error);
    }
}