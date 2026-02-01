import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export enum UserTypes {
    USER = "App\\Models\\User",
    GUEST = "App\\Models\\GuestUser",
}

export interface Auth {
    user: User;
    user_type: UserTypes;
    role: UserRole;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    auth: Auth;
    [key: string]: unknown;
};

export interface User {
    id: string;
    first_name: string;
    middle_name: string | null;
    last_name: string;
    full_name: string;
    email: string;
    last_login_at: Date | null;
    [key: string]: unknown; // This allows for additional properties...
}

export type BreadcrumbItemType = BreadcrumbItem;
