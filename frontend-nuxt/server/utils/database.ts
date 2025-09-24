import mariadb from "mariadb";
import type { Pool } from "mariadb";

/**
 * Create a connection pool to the database using the runtime configuration.
 * @returns {Pool} The database connection pool.
 */
export async function database(profile: "skp" | "ska"): Promise<Pool> {
    try {
        const config = useRuntimeConfig();
        const databaseName = profile === "skp" ? config.databaseNameSkp : config.databaseNameSka;
        return mariadb.createPool({
            "host": config.databaseHost,
            "port": Number(config.databasePort),
            "database": databaseName,
            "user": config.databaseUsername,
            "password": config.databasePassword,
            "multipleStatements": true,
            "connectionLimit": 15,
        });
    } catch (error: any) {
        logError(error);
        throw new Error("Something went wrong while connecting to the database.");
    }
}
