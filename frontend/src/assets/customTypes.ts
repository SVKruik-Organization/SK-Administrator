// Login Response
export type UserData = {
    "firstName": string,
    "lastName": string,
    "role": string,
    "imageUrl": string
}

// Pop-up Item
export type PopupItem = {
    "id": string,
    "type": PromptTypes,
    "message": string,
    "expiryMilliseconds": number
}

// Prompt/Informational Message Types
export enum PromptTypes {
    info = "info",
    success = "success",
    warning = "warning",
    danger = "danger"
}

// Navbar Notifications
export type NotificationItem = {
    "ticket": string,
    "type": PromptTypes,
    "message": string,
    "unread": boolean,
    "source": string,
    "date": Date
}

// Global UI Themes
export enum UIThemes {
    Default = "default",
    Carbon = "carbon",
    Monokai = "monokai"
}

// Team Subscription Tiers
export enum SubscriptionTiers {
    basic = "Basic",
    professional = "Professional",
    enterprise = "Enterprise"
}

// Tab Component Item
export type TabComponentItem = {
    "title": string,
    "route": string,
}

// Operator Member
export type OperatorUser = {
    "memberId": number,
    "snowflake": string,
    "username": string,
    "email": string,
    "service_tag": string,
    "date_creation": Date,
    "date_update": Date,
    "status": boolean,
    [key: string]: string | number | boolean | Date;
}

// Lower/Upperbounds for Pagination
export type PaginationValues = {
    "lowerBound": number,
    "upperBound": number
}

// Table Header Element
export type tableColumn = {
    "title": string,
    "comparator": string,
    "id": number
}

// Date Formatter
export type DateFormat = {
    "date": string,
    "time": string,
    "today": Date
}