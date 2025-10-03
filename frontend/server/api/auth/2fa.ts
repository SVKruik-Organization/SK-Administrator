import type { Pool } from "mariadb";
import { database } from "#imports";
import { z } from "zod";
import { LoginResponse, ProfileData, UserTypes } from "~/assets/customTypes";
import { createUserSession } from "#imports";
import { formatApiError } from "~/utils/format";
import { getProfileData } from "#imports";

// Validation schema for the request body
const bodySchema = z.object({
    email: z.email(),
    pin: z.string().length(6),
});

/**
 * Submit the 2FA code for verification
 * If successful, creates a session for the user
 * See /auth.d.ts for the session type
 */
export default defineEventHandler(async (event): Promise<LoginResponse> => {
    try {
        const parseResult = bodySchema.safeParse(await readBody(event));
        if (!parseResult.success) throw new Error("The form is not completed correctly. Please try again.", { cause: { statusCode: 1400 } });
        const { email, pin } = parseResult.data;

        // Retrieve the verification code from the database
        const connection: Pool = await database("ska");
        const codeResponse: Array<200> = await connection.query("SELECT 200 FROM user_verification WHERE user_email = ? AND pin = ? AND reason = '2fa';", [email, pin]);
        if (!codeResponse.length) throw new Error("The provided code is incorrect or has expired. Please check your credentials and try again.", { cause: { statusCode: 1401 } });

        // Retrieve the user from the database
        const response: Array<{
            "id": number,
            "first_name": string,
            "last_name": string,
            "password": string,
            "image_name": string,
        }> = await connection.query("SELECT id, first_name, last_name, password, image_name FROM user WHERE email = ?;", [email]);

        // Validate the response
        const user = response[0];
        if (!user) throw new Error("Email or password is incorrect. Please check your credentials and try again.", { cause: { statusCode: 1401 } });

        const profileData: ProfileData = await getProfileData(user.id, 0, false, connection);

        // Create the session
        const session = await createUserSession(event, {
            "id": user.id,
            "firstName": user.first_name,
            "lastName": user.last_name,
            "email": email,
            "type": UserTypes.USER,
            "imageName": user.image_name,
        }, connection);

        await connection.end();
        return {
            "user": session,
            "activeProfileId": profileData.activeProfileId,
            "firstItemUrl": profileData.firstItemUrl,
            "profiles": profileData.profiles,
            "topItems": profileData.topItems,
            "modules": profileData.modules,
            "language": profileData.language,
        };
    } catch (error: any) {
        throw formatApiError(error);
    }
});