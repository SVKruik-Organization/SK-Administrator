import { defineCronHandler } from "#nuxt/cron"
import { logData, logError } from "@svkruik/sk-platform-formatters";
import { CronJobTypes } from "@/assets/customTypes";
import { Pool, database } from "@svkruik/sk-platform-db-conn";

// Jobs that should be ran every minute
export default defineCronHandler("everyMinute", async () => {
    try {
        // Retrieve expired jobs from the database
        const connection: Pool = await database("central");
        const expiredJobs: Array<{
            "id": string,
            "type": CronJobTypes,
            "data": any,
        }> = await connection.query("SELECT id, type, data FROM scheduled_tasks WHERE status = 'PENDING_AUTO' AND scheduled_at <= NOW()");
        if (!expiredJobs || expiredJobs.length === 0) return;

        // Setup storage
        logData(`[CRON / Minute] Running with ${expiredJobs.length} pending jobs.`, "info");
        const resolvedJobs: Array<string> = [];
        const failedJobs: Array<string> = [];

        // Process each expired job
        for (const job of expiredJobs) {
            let response: boolean = false;

            switch (job.type) {
                // Add more cases for other job types here
                default:
                    break;
            }

            // Add the job to the correct array
            if (response) {
                resolvedJobs.push(job.id);
            } else failedJobs.push(job.id);
        }

        // Update the status of the jobs in the database
        if (resolvedJobs.length > 0) await connection.query("UPDATE scheduled_tasks SET status = 'COMPLETED' WHERE id IN (?);", [resolvedJobs]);
        if (failedJobs.length > 0) await connection.query("UPDATE scheduled_tasks SET status = 'FAILED' WHERE id IN (?);", [failedJobs]);

        logData(`[CRON / Minute] Completed ${resolvedJobs.length} job(s) and failed ${failedJobs.length} job(s).`, "info");
    } catch (error: any) {
        logError(error);
    }
});