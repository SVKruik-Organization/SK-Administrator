export enum UserTypes {
    USER = "App\\Models\\User",
    GUEST = "App\\Models\\GuestUser",
}

export enum Languages {
    EN = "en",
    NL = "nl",
    // KR = "kr",
}

// Pop-up Item
export type PopupItem = {
    "id": string,
    "type": PromptType,
    "message": string,
    "duration": number
}

// Prompt/Informational Message Types
export enum PromptType {
    info = "info",
    success = "success",
    warning = "warning",
    danger = "danger"
}

export type NotificationItem = {
    "id": string;
    "object_id": string;
    "object_type": UserTypes;
    "type": PromptType;
    "data": any;
    "source": string;
    "url": string;
    "is_silent": boolean;
    "created_at": Date;
    "updated_at": Date | null;
    "deleted_at": Date | null;
};

// Global UI Themes
export enum UIThemes {
    default = "default",
    carbon = "carbon",
    monokai = "monokai"
}

export type TabComponentItem = {
    "title": string,
    "route": string,
}

export enum CronJobTypes {
    MAINTENANCE,
    BACKUP,
    REPORT,
    NOTIFICATION,
    OTHER,
}

export enum CronJobStatus {
    PENDING_AUTO,
    PENDING_MANUAL,
    RUNNING,
    COMPLETED,
    FAILED,
}

// MeiliSearch Response
export type SearchResponse = {
    "results": Array<{
        "id": number;
        "username"?: string;
        "name"?: string;
        "title"?: string;
        "description"?: string;
        "avatar"?: string;
        "_federation": {
            "indexUid": string;
            "queriesPosition": number;
            "weightedRankingScore": number;
        };
    }>;
    "count": number;
    "duration": number;
};

export enum FileTypes {
    userAvatar = "users/avatar",
}

export enum NotificationTypes {
    initialize = "initialize",
    acknowledge = "acknowledge",
}

export type ProfileData = {
    "activeProfileId": string,
    "firstItemUrl": string,
    "profiles": Array<Profile>,
    "topLinks": Array<TopLink>,
    "modules": Array<Module>,
    "language": Languages,
}

export type Profile = {
    "id": string,
    "name": string,
    "description": string,
    "position": number,
    "last_usage_at": Date | null,
}

export type TopLink = {
    "name": { [lang in Languages]: string },
    "icon": string,
}

export type Module = {
    "id": string,
    "name": { [lang in Languages]: string },
    "icon": string,
    "links"?: Array<{ [lang in Languages]: string }>,
}