import mariadb from "mariadb";
import type { Pool } from "mariadb";

const validDatabases: Array<string> = ["skp", "central", "sso"];

/**
 * Create a connection pool to the database using the runtime configuration.
 * @returns {Pool} The database connection pool.
 */
export async function database(profile: "skp" | "central"): Promise<Pool> {
    try {
        const config = useRuntimeConfig();
        if (!validDatabases.includes(profile)) throw new Error("Invalid database profile specified.");
        return mariadb.createPool({
            "host": config.databaseHost,
            "port": Number(config.databasePort),
            "database": profile,
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
