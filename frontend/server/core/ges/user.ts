import { Pool } from "mariadb/*";
import { Languages, UserTypes } from "~/assets/customTypes";
import { H3Event } from "h3";

type LoginConfig = {
    disableEndConnection?: boolean; // true to disable ending the DB connection after login
    language?: Languages; // Language preference for the user session
}

export class UserEntity {
    id: number | null = null;
    email: string | null = null;
    database: Pool;

    constructor(id: number | null, email: string | null, database: Pool) {
        this.id = id;
        this.email = email;
        this.database = database;
    }

    async login(event: H3Event, config?: LoginConfig): Promise<void> {
        if (this.id === null && this.email === null) throw new Error("Either ID or email must be provided to fetch user.", { cause: { statusCode: 1400 } });

        // Fetch additional PII
        const additionalData: Array<{
            "id": number,
            "first_name": string,
            "last_name": string,
            "email": string,
            "image_name": string,
        }> = await this.database.query("SELECT id, first_name, last_name, email, image_name FROM user WHERE id = ? OR email = ?;", [this.id, this.email]);
        if (!additionalData.length) throw new Error("Email or password is incorrect. Please check your credentials and try again.", { cause: { statusCode: 1401 } });
        this.id = additionalData[0].id;
        this.email = additionalData[0].email;

        // Create the session
        await createUserSession(event, {
            "id": this.id,
            "firstName": additionalData[0].first_name,
            "lastName": additionalData[0].last_name,
            "email": this.email,
            "type": UserTypes.USER,
            "imageName": additionalData[0].image_name,
            "language": config?.language || Languages.EN
        }, this.database);

        if (!config?.disableEndConnection) await this.database.end();
    }
}