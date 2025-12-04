import { logError } from "@svkruik/sk-platform-formatters";
import { config } from "@svkruik/sk-platform-db-conn";

/**
 * Nitro plugin to configure the database connection settings.
 * 
 * @see https://github.com/SVKruik-Organization/Uplink
 */
export default defineNitroPlugin(async (_nitroApp) => {
    try {
        const runtimeConfig = useRuntimeConfig();
        config({
            "databaseHost": runtimeConfig.databaseHost,
            "databasePort": runtimeConfig.databasePort,
            "databaseUsername": runtimeConfig.databaseUsername,
            "databasePassword": runtimeConfig.databasePassword
        });
    } catch (error: any) {
        logError(error);
    }
});