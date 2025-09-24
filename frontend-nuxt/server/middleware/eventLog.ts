import type { Pool } from "mariadb";
import { database } from "~/server/utils/database";
import { log } from "../utils/logger";

export default defineEventHandler(async (event) => {
    // Only for CUD operations
    if (event.node.req.method !== "GET") {
        try {
            const data = {
                objectType: "not-logged-in",
                objectId: null as number | null,
                description: null as string | null,
                endpoint: event.node.req.url || null,
            }

            // Check if the user is logged in
            const session = await getUserSession(event);
            if (session && session.user) {
                data.objectType = session.user.role;
                data.objectId = session.user.id;
                data.description = session.user.firstName + " " + session.user.lastName;
            }

            const connection: Pool = await database("ska");
            await connection.query("INSERT INTO event_log (object_type, object_id, description, endpoint) VALUES (?, ?, ?, ?)", [...Object.values(data)]);
            await connection.end();
            log(`New request: ${JSON.stringify(data)}`, "info");
        } catch (error: any) {
            logError(error);
        }
    };
});