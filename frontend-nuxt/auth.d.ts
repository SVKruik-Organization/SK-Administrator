import type { UserTypes } from "./assets/customTypes";

declare module "#auth-utils" {
    // General User
    interface User {
        id: number;
        firstName: string;
        lastName: string;
        email: string | undefined;
        role: UserTypes;
        imageUrl: string | undefined;
    }

    // Session
    interface UserSession {
        loggedInAt: Date;
    }
}

export { }