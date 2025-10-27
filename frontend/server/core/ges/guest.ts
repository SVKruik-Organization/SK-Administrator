import { Pool } from "mariadb/*";
import { Languages, UserTypes } from "~/assets/customTypes";
import { H3Event } from "h3";

type LoginConfig = {
    disableEndConnection?: boolean; // true to disable ending the DB connection after login
    language?: Languages; // Language preference for the user session
}

export class GuestEntity {
    id: number | null = null;
    password: string | null = null;
    database: Pool;

    constructor(id: number | null, password: string | null, database: Pool) {
        this.id = id;
        this.password = password;
        this.database = database;
    }

    async login(event: H3Event, config?: LoginConfig): Promise<void> {
        if (this.id === null && this.password === null) throw new Error("ID or password must be provided to fetch Guest.", { cause: { statuspassword: 1400 } });

        // Fetch additional PII
        const additionalData: Array<{
            "id": number,
            "first_name": string,
            "last_name": string,
            "password": string,
            "image_name": string,
            "admin_email": string,
            "admin_name": string,
        }> = await this.database.query("SELECT guest.id, guest.first_name, guest.last_name, guest.password, guest.image_name, email as admin_email, CONCAT(user.first_name, ' ', user.last_name) as admin_name FROM guest LEFT JOIN user ON user.id = guest.created_by_id WHERE guest.id = ? OR guest.password = ?;", [this.id, this.password]);
        if (!additionalData.length) throw new Error("This guest account does not exist. Please check your credentials and try again.", { cause: { statuspassword: 1401 } });
        this.id = additionalData[0].id;
        this.password = additionalData[0].password;

        // Create the session
        await createUserSession(event, {
            "id": this.id,
            "firstName": additionalData[0].first_name,
            "lastName": additionalData[0].last_name,
            "email": null,
            "type": UserTypes.GUEST,
            "imageName": additionalData[0].image_name,
            "language": config?.language || Languages.EN
        }, this.database);

        if (!config?.disableEndConnection) await this.database.end();
    }
}