export enum UserTypes {
    USER = "User",
    GUEST = "Guest",
}

export enum Languages {
    EN = "en",
    NL = "nl",
    // KR = "kr",
}

// Pop-up Item
export type PopupItem = {
    "id": string,
    "type": PromptTypes,
    "message": string,
    "duration": number
}

// Prompt/Informational Message Types
export enum PromptTypes {
    info = "info",
    success = "success",
    warning = "warning",
    danger = "danger"
}

export type NotificationItem = {
    "user_id": number;
    "type": NotificationTypes;
    "level": PromptTypes;
    "data": {
        "message": string;
        "details"?: string;
    };
    "url": string;
    "source": string;
    "is_read": boolean;
    "is_silent": boolean;
    "ticket": string;
    "date_expiry": Date;
    "date_creation": Date;
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

export enum LogTypes {
    info = "INFO",
    warning = "WARNING",
    alert = "ALERT",
    error = "ERROR",
    fatal = "FATAL",
    none = "NONE",
}

export enum CronJobTypes {
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

// Uplink Network Payload
export type UplinkMessage = {
    "sender": string;
    "recipient": string;
    "triggerSource": string;
    "reason": string;
    "task": string;
    "content": string;
    "timestamp": Date;
};

export enum FileTypes {
    userAvatar = "users/avatar",
}

export enum NotificationTypes {
    initialize = "initialize",
    acknowledge = "ack",
}

export type ProfileData = {
    "activeProfileId": number,
    "firstItemUrl": string,
    "profiles": Array<Profile>,
    "topLinks": Array<TopLink>,
    "modules": Array<Module>,
    "language": Languages,
}

export type Profile = {
    "id": number,
    "name": string,
    "description": string,
    "position": number,
    "date_last_usage": Date | null,
}

export type TopLink = {
    "name": { [lang in Languages]: string },
    "icon": string,
}

export type Module = {
    "id": number,
    "name": { [lang in Languages]: string },
    "icon": string,
    "links"?: Array<{ [lang in Languages]: string }>,
}