import { Pool } from "mariadb";
import { CronJobTypes } from "~/assets/customTypes";

export async function registerCron(payload: {
    "type": keyof typeof CronJobTypes;
    "data": object;
    "date_schedule": Date;
}, connection: Pool): Promise<void> {
    await connection.query("INSERT INTO scheduled_task (type, data, date_schedule) VALUES (?, ?, ?)", [
        payload.type,
        payload.data,
        payload.date_schedule,
    ]);
}