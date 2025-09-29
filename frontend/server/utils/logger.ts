import fs from "fs";
import { getDate } from "~/utils/date";
import { LogTypes } from "~/assets/customTypes";
import { Pool } from "mariadb";

/**
 * Log messages to the log file.
 * 
 * @param data The data to log to the file.
 * @param rawType The type of message. For example: warning, alert, info, fatal, none.
 * @returns Status of the operation.
 */
export function log(data: string, type: keyof typeof LogTypes): boolean {
    try {
        let logData: string;

        if (type === "none") {
            logData = `${data}\n`;
        } else logData = `${getDate().time} [${type.toUpperCase()}] ${data}\n`;

        write(logData);
        console.log(logData);
        return true;
    } catch (error: any) {
        logError(error);
        return false;
    }
}

/**
 * Log error messages to the log file.
 * 
 * @param data The error to write to the file.
 */
export function logError(data: Error): void {
    const logData: string = `${getDate().time} [${LogTypes.error}] ${typeof data === "string" ? data : data.stack}\n`;
    write(logData);
    console.error(logData);
}

/**
 * Perist GES transactions to the database.
 * @param data The data to log to the database.
 * @param database An active database connection.
 */
export async function logGES(data: {
    "objectType": string;
    "objectId": number | null;
    "description": string;
    "source": string;
}, database: Pool): Promise<void> {
    await database.query("INSERT INTO event_log (object_type, object_id, description, endpoint) VALUES (?, ?, ?, ?)", [
        data.objectType,
        data.objectId,
        data.description,
        `GES - ${data.source}`,
    ]);
}

/**
 * The writing to the log file itself.
 * 
 * @param data The text to write to the file.
 */
function write(data: string): void {
    const logDir = './logs';
    if (!fs.existsSync(logDir)) fs.mkdirSync(logDir, { recursive: true });
    fs.appendFile(`${logDir}/${getDate().date}.log`, data, (error) => {
        if (error) console.error(`${getDate().time} [${LogTypes.error}] Error appending to log file.`);
    });
}
