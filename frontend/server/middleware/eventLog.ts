import { logData, logError } from "@svkruik/sk-platform-formatters";
import { database } from "@svkruik/sk-platform-db-conn";
import { UserTypes } from "~/assets/customTypes";

export default defineEventHandler(async (event) => {
    // Only for CUD operations
    if (event.node.req.method !== "GET") {
        try {
            const data = {
                objectType: null as UserTypes | null,
                objectId: null as string | null,
                description: null as string | null,
                endpoint: event.node.req.url || null,
                method: event.node.req.method || null,
                ipAddress: event.node.req.socket.remoteAddress || null,
            }

            // Check if the user is logged in
            const session = await getUserSession(event);
            if (session && session.user) {
                data.objectType = session.user.type;
                data.objectId = session.user.id;
                data.description = session.user.fullName;
            }

            const connection = await database("central");
            await connection.query("INSERT INTO event_logs (object_type, object_id, description, endpoint, method, ip_address) VALUES (?, ?, ?, ?, ?, ?)", [...Object.values(data)]);
            logData(`New request: ${JSON.stringify(data)}`, "info");
        } catch (error: any) {
            logError(error);
        }
    };
});