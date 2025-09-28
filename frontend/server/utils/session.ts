import type { H3Event } from "h3";
import { Pool } from "mariadb";
import { Languages, UserData, UserTypes } from "~/assets/customTypes";

/**
 * Creates and returns a user session.
 * Also updates the last login date in the database.
 * 
 * @param event The request event
 * @param user The user data to store in the session 
 * @param connection The database connection to use
 * @returns The user data stored in the session
 */
export async function createUserSession(event: H3Event, user: {
    id: number;
    firstName: string,
    lastName: string | undefined,
    email: string | null,
    type: UserTypes,
    imageName: string | null
    language: Languages
}, connection: Pool): Promise<UserData> {
    await setUserSession(event, {
        user: {
            "id": user.id,
            "firstName": user.firstName,
            "lastName": user.lastName,
            "email": user.email,
            "type": user.type,
            "imageName": user.imageName,
            "language": user.language,
        },
        loggedInAt: new Date(),
    });

    // Update the last login date in the database
    const tableName: string = user.type === UserTypes.USER ? "user" : "guest";
    await connection.query(`UPDATE ${tableName} SET date_last_login = CURRENT_TIMESTAMP WHERE id = ?;`, [user.id]);

    // Return the user data
    return (await getUserSession(event)).user as UserData;
}