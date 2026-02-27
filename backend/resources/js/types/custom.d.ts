export enum UserTypes {
    USER = "App\\Models\\User",
    GUEST = "App\\Models\\GuestUser",
}

export type UserProfile = {
    id: string;
    name: Record<string, string>;
    description: Record<string, string>;
    position: number;
    language: string;
    last_usage_at: Date | null;
}

export type LoadedUserProfile = UserProfile & {
    modules: Array<Module>;
}

export type Module = {
    id: string;
    name: Record<string, string> | null;
    icon: string | null;
    items: Array<ModuleItem>;
    url: string;
}

export type ModuleItem = {
    id: string;
    name: Record<string, string>;
    position: number;
    icon: string | null;
    url: string;
}