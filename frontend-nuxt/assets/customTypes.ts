// Login Response
export type UserData = {
    "id": number,
    "firstName": string,
    "lastName": string,
    "email": string,
    "role": string,
    "imageUrl": string
}

export enum UserTypes {
    ADMIN = "Administrator",
    GUEST = "Guest",
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
    "data": any; // JSON payload
    "url": string;
    "source": string;
    "is_read": boolean;
    "ticket": string;
    "date_expiry": Date;
    "date_creation": Date;
};

// Global UI Themes
export enum UIThemes {
    Default = "default",
    Carbon = "carbon",
    Monokai = "monokai"
}

export type TabComponentItem = {
    "title": string,
    "route": string,
}

export type OperatorUser = {
    "id": number,
    "snowflake": string,
    "username": string,
    "email": string,
    "service_tag": string,
    "date_creation": Date,
    "date_update": Date,
    "status": boolean,
    [key: string]: string | number | boolean | Date;
}

export type PaginationValues = {
    "lowerBound": number,
    "upperBound": number
}

export type TableColumn = {
    "title": string,
    "comparator": string,
    "id": number
}

export type DateFormat = {
    "date": string,
    "time": string,
    "today": Date
}

export type BooleanTableData = {
    "trueColor": PromptTypes,
    "falseColor": PromptTypes,
    "trueMessage": string,
    "falseMessage": string
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
    results: Array<{
        id: number;
        username?: string;
        name?: string;
        title?: string;
        description?: string;
        avatar?: string;
        _federation: {
            indexUid: string;
            queriesPosition: number;
            weightedRankingScore: number;
        };
    }>;
    count: number;
    duration: number;
};

// Uplink Network Payload
export type UplinkMessage = {
    sender: string;
    recipient: string;
    triggerSource: string;
    reason: string;
    task: string;
    content: string;
    timestamp: Date;
};

export enum FileTypes {
    userAvatar = "users/avatar",
}

export enum NotificationTypes {
    initialize = "initialize",
    acknowledge = "ack",
}
