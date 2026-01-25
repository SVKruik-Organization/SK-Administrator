import { CronJobTypes, CronJobStatus } from "@/assets/customTypes";
import { Pool } from "@svkruik/sk-platform-db-conn";
import { randomUUID } from "crypto";

export async function registerCron(payload: {
    "type": CronJobTypes;
    "data": object;
    "status": CronJobStatus;
    "scheduled_at": Date;
}, connection: Pool): Promise<void> {
    await connection.query("INSERT INTO scheduled_tasks (id, type, status, data, scheduled_at) VALUES (?, ?, ?, ?, ?)", [
        randomUUID(),
        payload.type,
        payload.status,
        payload.data,
        payload.scheduled_at,
    ]);
}