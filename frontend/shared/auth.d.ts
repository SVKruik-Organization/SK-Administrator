import type { Languages, UserTypes } from "~/assets/customTypes";

declare module "#auth-utils" {
    // General User
    interface User {
        id: number;
        fullName: string;
        email: string;
        type: UserTypes;
        language: Languages;
    }

    // Session
    interface UserSession {
        loggedInAt: Date;
    }
}

export { }