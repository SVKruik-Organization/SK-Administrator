import type { Pool } from "mariadb";
import { database } from "#imports";
import { log } from "#imports";

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
                data.objectType = session.user.type;
                data.objectId = session.user.id;
                data.description = session.user.firstName + " " + session.user.lastName;
            }

            const connection: Pool = await database("central");
            await connection.query("INSERT INTO event_log (object_type, object_id, description, endpoint) VALUES (?, ?, ?, ?)", [...Object.values(data)]);
            log(`New request: ${JSON.stringify(data)}`, "info");
        } catch (error: any) {
            logError(error);
        }
    };
});