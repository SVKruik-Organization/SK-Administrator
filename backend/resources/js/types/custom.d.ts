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
    name: Record<string, string>;
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

export type BreadcrumbItem = {
    label: string;
    url: string;
}

export enum PromptType {
    info = "info",
    success = "success",
    warning = "warning",
    danger = "danger"
}

export type NotificationItem = {
    id: string;
    object_id: string;
    object_type: UserTypes;
    type: PromptType;
    data: any;
    source: string;
    url: string;
    is_silent: boolean;
    created_at: Date;
    updated_at: Date | null;
    deleted_at: Date | null;
}

export type ObjectTableData = {
    data: Array<any>;
    columns: Record<string, string>;
    page: number;
    perPage: number;
    totalRecords: number;
    totalPages: number;
    hasMore: boolean;
}

export type OrderedTableData = {
    data: Array<Record<string, any>>;
    columns: Record<string, string>;
    totalRecords: number;
}