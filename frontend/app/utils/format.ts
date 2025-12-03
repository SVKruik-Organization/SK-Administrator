import { H3Error } from "h3";
import { Languages } from "@/assets/customTypes";
import { FetchError } from "ofetch";
import { logError } from "@svkruik/sk-platform-formatters";

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
 * Formats an H3 (backend) error for the popup.
 * Use this function only in the backend.
 * Specifically for backend errors.
 * @param error The error to handle.
 * @returns Formatted H3 error.
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
 * Special formatting for inter-backend communication errors.
 * @param error The error to handle.
 * @returns Formatted H3 error or throws an error.
 */
export function formatInterBackendError(error: any): H3Error | Error {
    if (error instanceof FetchError) {
        const status = (error.response?.status ?? error.status ?? 500);
        const message = (error.data?.message ?? error.message ?? "Something went wrong on our end. Please try again later.");
        throw createError({ statusCode: status, message });
    }
    throw new Error("Something went wrong on our end. Please try again later.", {
        cause: { statusCode: 1500 },
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
