import { Languages, UserTypes } from "@/assets/customTypes";
import { H3Event } from "h3";
import { Pool } from "@svkruik/sk-platform-db-conn";

type LoginConfig = {
    language?: Languages; // Language preference for the user session
}

export class UserEntity {
    id: string | null = null;
    email: string | null = null;
    database: Pool;

    constructor(id: string | null, email: string | null, database: Pool) {
        this.id = id;
        this.email = email;
        this.database = database;
    }

    async login(event: H3Event, config?: LoginConfig): Promise<void> {
        if (this.id === null && this.email === null) throw new Error("Either ID or email must be provided to fetch user.", { cause: { statusCode: 1400 } });

        // Fetch additional PII
        const additionalData: Array<{
            "id": string,
            "fullName": string,
            "email": string,
        }> = await this.database.query("SELECT id, full_name, email FROM users WHERE id = ? OR email = ?;", [this.id, this.email]);
        if (!additionalData.length || !additionalData[0]) throw new Error("Email or password is incorrect. Please check your credentials and try again.", { cause: { statusCode: 1401 } });
        this.id = additionalData[0].id;
        this.email = additionalData[0].email;

        // Create the session
        await createUserSession(event, {
            "id": this.id,
            "fullName": additionalData[0].fullName,
            "email": this.email,
            "type": UserTypes.USER,
            "language": config?.language || Languages.EN
        }, this.database);
    }
}