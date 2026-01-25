import { z } from "zod";
import { ProfileData, UserTypes } from "@/assets/customTypes";
import { formatApiError, formatInterBackendError } from "@/utils/format";
import { GuestEntity } from "~~/server/core/ges/guest";
import { UserEntity } from "~~/server/core/ges/user";
import { Pool, database } from "@svkruik/sk-platform-db-conn";

// Validation schema for the request body
const bodySchema = z.object({
    token: z.string()
});

const userInformationSchema = z.object({
    object_id: z.string(),
    object_type: z.string(),
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
        let userInformation: z.infer<typeof userInformationSchema>;
        try {
            userInformation = await $fetch(`${config.public.authProviderURL}/api/auth/administrator/validate-token`, {
                method: "POST",
                body: { token },
                headers: { "Content-Type": "application/json" },
            });
        } catch (error: any) {
            throw formatInterBackendError(error);
        }

        if (!userInformation) throw new Error("The provided token is invalid. Please try again.", { cause: { statusCode: 1401 } });
        const userInformationParse = userInformationSchema.safeParse(userInformation);
        if (!userInformationParse.success || !Object.values(UserTypes).includes(userInformationParse.data.object_type as UserTypes)) throw new Error("The provided token is invalid. Please try again.", { cause: { statusCode: 1401 } });

        const { object_id, object_type } = userInformationParse.data;
        const connection: Pool = await database("central");

        const entity: UserEntity | GuestEntity = object_type === UserTypes.USER
            ? new UserEntity(object_id, null, connection)
            : new GuestEntity(object_id, null, connection);
        const profileData: ProfileData = await getProfileData(object_id, object_type as UserTypes, null, false, connection);

        // Login the user/guest
        await entity.login(event, {
            language: profileData.language,
        });
        return profileData;
    } catch (error: any) {
        throw formatApiError(error);
    }
});