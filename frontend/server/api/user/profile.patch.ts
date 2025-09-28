import type { Pool } from "mariadb";
import { database } from "#imports";
import { z } from "zod";
import { formatApiError } from "~/utils/format";
import { ProfileData } from "~/assets/customTypes";
import { getProfileData } from "#imports";

// Validation schema for the request body
const bodySchema = z.object({
    profileId: z.string().transform((val) => parseInt(val, 10))
});

/**
 * Get the user profile data for a specific profile ID
 * Used for the sidebar.
 */
export default defineEventHandler(async (event): Promise<ProfileData> => {
    try {
        const parseResult = bodySchema.safeParse(getQuery(event));
        if (!parseResult.success) throw new Error("The form is not completed correctly. Please try again.", { cause: { statusCode: 1400 } });
        const { profileId } = parseResult.data;

        // Retrieve the profile from the database
        const connection: Pool = await database("ska");
        const userId: number | undefined = (await getUserSession(event)).user?.id;
        if (!userId) throw new Error("You must be logged in to access this resource.", { cause: { statusCode: 1401 } });
        const profileData: ProfileData = await getProfileData(userId, profileId, connection);

        await connection.end();
        return {
            "activeProfileId": profileId,
            "profiles": profileData.profiles,
            "topItems": profileData.topItems,
            "modules": profileData.modules,
        };
    } catch (error: any) {
        throw formatApiError(error);
    }
});