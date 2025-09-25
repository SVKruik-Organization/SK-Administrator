import type { Pool } from "mariadb";
import { database } from "~/server/utils/database";
import { z } from "zod";
import { type UserData, UserTypes } from "~/assets/customTypes";
import { createUserSession } from "~/server/utils/session";
import { formatApiError } from "~/utils/format";

// Validation schema for the request body
const bodySchema = z.object({
    code: z.string().length(32),
});

/**
 * Login a guest user using a code.
 * See /auth.d.ts for the session type
 */
export default defineEventHandler(async (event): Promise<UserData> => {
    try {
        const parseResult = bodySchema.safeParse(await readBody(event));
        if (!parseResult.success) throw new Error("The form is not completed correctly. Please try again.", { cause: { statusCode: 1400 } });
        const { code } = parseResult.data;

        // Retrieve the user from the database
        const connection: Pool = await database("ska");
        const response: Array<{
            "id": number,
            "first_name": string,
            "last_name": string,
            "password": string,
            "image_name": string
        }> = await connection.query("SELECT id, first_name, last_name, password, image_name FROM guest WHERE password = ?;", [code]);

        // Validate the response
        if (response.length === 0) throw new Error("This guest account does not exist. Please check your credentials and try again.", { cause: { statusCode: 1401 } });
        const user = response[0];

        // Create the session
        const session = await createUserSession(event, {
            "id": user.id,
            "firstName": user.first_name,
            "lastName": user.last_name,
            "email": null,
            "type": UserTypes.GUEST,
            "imageName": user.image_name,
        }, connection);
        await connection.end();
        return session;
    } catch (error: any) {
        throw formatApiError(error);
    }
});