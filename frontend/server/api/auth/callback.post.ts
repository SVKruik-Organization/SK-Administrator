import { Pool } from "mariadb/*";
import { z } from "zod";
import { ProfileData } from "~/assets/customTypes";
import { formatApiError } from "~/utils/format";
import { UserEntity } from "~~/server/core/ges/user";

// Validation schema for the request body
const bodySchema = z.object({
    token: z.string()
});

/**
 * Get the user profile data for a specific profile ID
 * Used for the sidebar.
 */
export default defineEventHandler(async (event): Promise<ProfileData> => {
    try {
        const parseResult = bodySchema.safeParse(await readBody(event));
        if (!parseResult.success) throw new Error("The form is not completed correctly. Please try again.", { cause: { statusCode: 1400 } });
        const { token } = parseResult.data;
        const config = useRuntimeConfig();

        // Retrieve user ID by token from Overway
        const userId: number = await $fetch(`${config.public.authProviderURL}/api/auth/administrator/validate-token`, {
            method: "POST",
            body: {
                token
            },
            headers: {
                "Content-Type": "application/json",
            },
        });
        if (!userId || typeof userId !== "number") throw new Error("Invalid token provided.", { cause: { statusCode: 1401 } });
        const connection: Pool = await database("central");


        // Retrieve the user and profile data
        const user: UserEntity = new UserEntity(userId, null, connection);
        const profileData: ProfileData = await getProfileData(userId, 0, false, connection);

        await user.login(event, {
            language: profileData.language,
        });
        return profileData;
    } catch (error: any) {
        throw formatApiError(error);
    }
});