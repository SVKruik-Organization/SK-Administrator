import { User, UserSession } from "#auth-utils";
import type { H3Event } from "h3";
import { UserTypes } from "@/assets/customTypes";
import { getSessionTTL } from "@/utils/settings";
import { Pool } from "@svkruik/sk-platform-db-conn";

/**
 * Creates and returns a user session.
 * Also updates the last login date in the database.
 * 
 * @param event The request event
 * @param user The user data to store in the session 
 * @param connection The database connection to use
 * @returns The user data stored in the session
 */
export async function createUserSession(event: H3Event, user: User, connection: Pool): Promise<User> {
    const userSession: UserSession = await replaceUserSession(event, {
        "user": user,
        "loggedInAt": new Date(),
    }, getSessionTTL(user.type));

    // Update the last login date in the database
    const tableName: string = user.type === UserTypes.USER ? "users" : "guest_users";
    await connection.query(`UPDATE ${tableName} SET last_login_at = CURRENT_TIMESTAMP WHERE id = ?;`, [user.id]);

    // Return the user data
    return userSession.user as User;
}