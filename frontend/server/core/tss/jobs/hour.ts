import { defineCronHandler } from "#nuxt/cron"
import { Pool } from "mariadb";

export default defineCronHandler("hourly", async () => {
    try {
        const connection: Pool = await database("central");
        await connection.query("DELETE FROM user_notification WHERE date_expiry < NOW();");

        log(`[CRON / Hour] Completed data cleaning process.`, "info");
    } catch (error: any) {
        logError(error);
    }
});