import type { Pool } from "mariadb";

export class User {
    private id: number;
    private database: Pool;

    /**
     * A User is a person who uses the system. They have access to everything within their scope.
     * @param id The ID of the User.
     * @param database The database connection pool.
     */
    constructor(id: number, database: Pool) {
        this.id = id;
        this.database = database;
    }
}