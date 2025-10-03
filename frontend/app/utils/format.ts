import { H3Error } from "h3";
import { Languages } from "~/assets/customTypes";

/**
 * Converts a Date object to a human-readable time ago format.
 *
 * @param date Date object
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
 * @param num The number to format
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
 * Normalizes a URL by replacing spaces with hyphens and converting to lowercase.
 * @param url The URL to normalize.
 * @returns The normalized URL.
 */
export function normalizeUrl(url: string | { [lang in Languages]: string }): string {
    if (typeof url === "object") return url[Languages.EN].replaceAll(" ", "-").toLowerCase();
    return url.replaceAll(" ", "-").toLowerCase();
}

/**
 * Formats an H3 error for the popup notification.
 * Use this function only in the backend.
 * Specifically for backend errors.
 * @param error The error to handle.
 */
export function formatApiError(error: any): H3Error {
    const statusCode = error?.cause?.statusCode || (() => { logError(error); return 500; })();
    const internalErrorMessage = "Something went wrong on our end. Please try again later.";
    const formattedErrorMessage = statusCode === 500 ? internalErrorMessage : error?.message || internalErrorMessage;
    return createError({
        "statusCode": statusCode > 1000 ? statusCode - 1000 : statusCode,
        "message": formattedErrorMessage
    });
}

/**
 * Formats an error for the popup notification.
 * @param error The error to handle.
 */
export function formatError(error: any): Error {
    return createError({
        "statusCode": error.statusCode,
        "message": error?.data?.message || error?.message || "Something went wrong. Please try again later.",
    });
};
