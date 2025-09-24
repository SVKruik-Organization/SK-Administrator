import type { Pool } from "mariadb";
import { database } from "~/server/utils/database";
import { z } from "zod";
import { type UserData, UserTypes } from "~/assets/customTypes";
import { createUserSession } from "~/server/utils/session";
import { formatApiError } from "~/utils/format";

// Validation schema for the request body
const bodySchema = z.object({
    code: z.string().length(16),
});

/**
 * Login a guest user using a code.
 * See /auth.d.ts for the session type
 */
export default defineEventHandler(async (event): Promise<UserData | false> => {
    try {
        const parseResult = bodySchema.safeParse(await readBody(event));
        if (!parseResult.success) throw new Error("The form is not completed correctly. Please try again.", { cause: { statusCode: 1400 } });
        const { code } = parseResult.data;

        // Retrieve the user from the database
        const connection: Pool = await database("ska");
        const response: Array<{
            "id": number,
            "firstName": string,
            "lastName": string,
            "password": string,
        }> = await connection.query("SELECT id, first_name, last_name, password FROM guest WHERE code = ?;", [code]);

        // Validate the response
        if (response.length === 0) throw new Error("This guest account does not exist. Please check your credentials and try again.", { cause: { statusCode: 1401 } });
        const user = response[0];

        // Create the session
        const session = await createUserSession(event, {
            "id": user.id,
            "firstName": user.firstName,
            "lastName": user.lastName,
            "email": undefined,
            "role": UserTypes.GUEST,
            "imageUrl": undefined,
        }, connection);
        await connection.end();
        return session;
    } catch (error: any) {
        throw formatApiError(error);
    }
});