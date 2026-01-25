import { Languages, UserTypes } from "@/assets/customTypes";
import { H3Event } from "h3";
import { Pool } from "@svkruik/sk-platform-db-conn";

type LoginConfig = {
    language?: Languages; // Language preference for the user session
}

export class GuestEntity {
    id: string | null = null;
    email: string | null = null;
    database: Pool;

    constructor(id: string | null, email: string | null, database: Pool) {
        this.id = id;
        this.email = email;
        this.database = database;
    }

    async login(event: H3Event, config?: LoginConfig): Promise<void> {
        if (this.id === null && this.email === null) throw new Error("ID or email must be provided to fetch Guest.", { cause: { statusCode: 1400 } });

        // Fetch additional PII
        const additionalData: Array<{
            "id": string,
            "full_name": string,
            "email": string,
            "admin_email": string,
            "admin_name": string,
        }> = await this.database.query("SELECT guest_users.id, guest_users.full_name, guest_users.email AS guest_email, users.email as admin_email, users.full_name as admin_name FROM guest_users LEFT JOIN users ON users.id = guest_users.owner_id WHERE guest_users.id = ? OR guest_users.email = ?;", [this.id, this.email]);
        if (!additionalData.length || !additionalData[0]) throw new Error("This guest account does not exist. Please check your credentials and try again.", { cause: { statusCode: 1401 } });
        this.id = additionalData[0].id;
        this.email = additionalData[0].email;

        // Create the session
        await createUserSession(event, {
            "id": this.id,
            "fullName": additionalData[0].full_name,
            "email": this.email,
            "type": UserTypes.GUEST,
            "language": config?.language ?? Languages.EN
        }, this.database);
    }
}