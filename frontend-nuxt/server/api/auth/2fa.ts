import type { Pool } from "mariadb";
import { database } from "~/server/utils/database";
import { z } from "zod";
import { LoginResponse, Module, Profile, TopLink, type UserData, UserTypes } from "~/assets/customTypes";
import { createUserSession } from "~/server/utils/session";
import { formatApiError } from "~/utils/format";

// Validation schema for the request body
const bodySchema = z.object({
    email: z.email(),
    pin: z.string().length(6),
});

/**
 * Login a guest user using a code.
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

        // Create the session
        const session = await createUserSession(event, {
            "id": user.id,
            "firstName": user.first_name,
            "lastName": user.last_name,
            "email": email,
            "type": UserTypes.USER,
            "imageName": user.image_name,
        }, connection);

        // Retrieve the user profile
        const rawUserProfiles: Array<Profile> = await connection.query("SELECT id, name, position, date_last_usage FROM user_profile WHERE user_id = ? ORDER BY date_last_usage, position;", [user.id]);
        const activeProfile = rawUserProfiles[0];
        const userProfiles = rawUserProfiles.sort((a, b) => a.position - b.position);

        // Retrieve the modules and module items from the database
        const moduleData: Array<{
            "module_id": number,
            "module_name": string | null,
            "module_icon": string | null,
            "module_item_name": string,
            "module_item_icon": string | null,
        }> = await connection.query(`
            SELECT
                IFNULL(module.id, 0) as module_id,
                module.name AS module_name,
                module.icon AS module_icon,
                module_item.name AS module_item_name,
                module_item.icon AS module_item_icon
            FROM
                module
                LEFT JOIN user_profile_module ON module_id = module.id
                LEFT JOIN user_profile ON user_profile.id = user_profile_module.user_profile_id
                RIGHT JOIN module_item ON module_item.module_id = module.id
            WHERE user_profile.user_id = ? OR module_item.module_id IS NULL;`, [user.id]);
        const moduleMap = new Map<number, Module>();
        const topItems: Array<TopLink> = [];
        for (const rawModule of moduleData) {
            // Loose item (no module, just a top-level item)
            if (!rawModule.module_id || !rawModule.module_name) {
                topItems.push({
                    "name": rawModule.module_item_name,
                    "icon": rawModule.module_item_icon || "fa-link",
                });
                continue;
            }

            // Set the modules (categories)
            if (!moduleMap.has(rawModule.module_id)) moduleMap.set(rawModule.module_id, {
                "id": rawModule.module_id,
                "name": rawModule.module_name,
                "icon": rawModule.module_icon || "fa-folder",
                "links": [],
            });

            // Add the links to the modules
            moduleMap.get(rawModule.module_id)!.links!.push(rawModule.module_item_name);
        }
        const modules = Array.from(moduleMap.values());

        await connection.end();
        return {
            "user": session,
            "activeProfile": activeProfile.id,
            "profiles": userProfiles,
            "topItems": topItems,
            "modules": modules,
        };
    } catch (error: any) {
        throw formatApiError(error);
    }
});