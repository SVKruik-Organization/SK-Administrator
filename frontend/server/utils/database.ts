import { logError } from "@svkruik/sk-platform-formatters";
import mariadb from "mariadb";
import type { Pool } from "mariadb";

const validDatabases: Array<string> = ["skp", "central"];
declare global {
    var __db_pools__: Record<string, Pool> | undefined;
}

/**
 * Creates or returns a connection to the database using the runtime configuration.
 * @returns {Pool} The database connection pool.
 */
export async function database(profile: "skp" | "central"): Promise<Pool> {
    try {
        const config = useRuntimeConfig();
        if (!validDatabases.includes(profile)) throw new Error("Invalid database profile specified.");

        // Init and check cache container
        if (!globalThis.__db_pools__) globalThis.__db_pools__ = {};
        if (globalThis.__db_pools__[profile]) return globalThis.__db_pools__[profile];

        const pool = mariadb.createPool({
            host: config.databaseHost,
            port: Number(config.databasePort),
            database: profile,
            user: config.databaseUsername,
            password: config.databasePassword,
            multipleStatements: true,
            connectionLimit: 15,
        });

        globalThis.__db_pools__[profile] = pool;
        return pool;
    } catch (error: any) {
        logError(error);
        throw new Error("Something went wrong while connecting to the database.");
    }
}
