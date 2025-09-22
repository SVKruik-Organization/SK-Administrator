import { H3Error } from "h3";

/**
 * Converts a Date object to a human-readable time ago format.
 *
 * @param date - Date object
 * @returns Formatted time ago string
 */
export function formatTimeAgo(date: Date): string {
    const seconds = (Date.now() - date.getTime()) / 1000;
    if (seconds < 60) return "just now";
    if (seconds < 3600) return `${Math.floor(seconds / 60)}m ago`;
    if (seconds < 86400) return `${Math.floor(seconds / 3600)}h ago`;
    return `${Math.floor(seconds / 86400)}d ago`;
}

/**
 * Formats a number into a more readable format (e.g., 1K, 1M).
 *
 * @param num - The number to format
 * @returns Formatted string
 */
export function formatNumber(num: number): string {
    if (num >= 1e6) {
        return (num / 1e6).toFixed(1) + "M";
    } else if (num >= 1e3) {
        return (num / 1e3).toFixed(1) + "K";
    } else {
        return num.toString();
    }
}

/**
 * Formats an H3 error for the toast notification.
 * Specifically for backend errors.
 * @param error The error to handle.
 */
export function formatApiError(error: any): H3Error {
    const statusCode = error?.cause?.statusCode || (() => { logError(error); return 500; })();
    const internalErrorMessage = "Something went wrong on our end. Please try again later.";
    const formattedErrorMessage = statusCode === 500 ? internalErrorMessage : error?.message || internalErrorMessage;
    return createError({
        statusCode: statusCode > 1000 ? statusCode - 1000 : statusCode,
        statusMessage: formattedErrorMessage,
        data: {
            message: formattedErrorMessage,
        },
    });
}

/**
 * Formats an error for the toast notification.
 * @param error The error to handle.
 */
export function formatError(error: any): Error {
    return createError({
        statusCode: error.statusCode,
        message: error?.data?.message || error?.message || "Something went wrong. Please try again later.",
    });
};

/**
 * Determines the text color based on the luminance of the background color.
 * @param hexColor The hex color code of the background.
 * @returns The text color (black or white) based on the luminance of the background color.
 */
export function getTextColor(hexColor: string): string {
    const r: number = parseInt(hexColor.slice(1, 3), 16) / 255;
    const g: number = parseInt(hexColor.slice(3, 5), 16) / 255;
    const b: number = parseInt(hexColor.slice(5, 7), 16) / 255;

    const [R, G, B] = [r, g, b].map((c) =>
        c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4),
    );
    const luminance: number = 0.2126 * R + 0.7152 * G + 0.0722 * B;

    return luminance > 0.5 ? "text-main" : "text-font";
}
/**
 * @param dateString - Account birthday date
 * @description Checks if the given date is the same day and month as today.
 * @returns boolean - true if today is the same day and month as the date, false otherwise.
 */
export function isAccoundBirthday(dateString: string): boolean {
    const date = new Date(dateString);
    const today = new Date();
    return (
        date.getDate() === today.getDate() &&
        date.getMonth() === today.getMonth()
    );
}
