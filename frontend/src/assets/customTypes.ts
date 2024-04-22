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
    "type": PopupTypes,
    "message": string,
    "time": number
}

export enum PopupTypes {
    info = "info",
    success = "success",
    warning = "warning",
    danger = "danger"
}