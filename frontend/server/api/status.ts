/**
 * Return the status of the platform in shield badge format.
 * 
 * @see https://shields.io/
 * 
 * @returns {object} The status badge object.
 */
export default defineEventHandler(async (): Promise<{
    "schemaVersion": number;
    "label": string;
    "message": string;
    "color": string;
}> => {
    return {
        "schemaVersion": 1,
        "label": "SK Administrator Status",
        "message": "online",
        "color": "brightgreen"
    }
});