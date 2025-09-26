import type { UserTypes } from "~/assets/customTypes";

declare module "#auth-utils" {
    // General User
    interface User {
        id: number;
        firstName: string;
        lastName: string;
        email: string | null;
        type: UserTypes;
        imageName: string | null;
    }

    // Session
    interface UserSession {
        loggedInAt: Date;
    }
}

export { }