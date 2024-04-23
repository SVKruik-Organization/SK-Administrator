// Login Response
export type UserData = {
    "firstName": string,
    "lastName": string,
    "role": string,
    "imageUrl": string
}

// Usable Pop-up Item
export type PopupItem = {
    "id": string,
    "color": string,
    "message": string
}

// Constructable Pop-up Item
export type PopupPayload = {
    "type": PromptTypes,
    "message": string,
    "time": number
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
    "read": boolean,
    "source": string,
    "date": Date
}