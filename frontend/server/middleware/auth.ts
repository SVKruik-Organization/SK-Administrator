import { formatApiError } from "~/utils/format";

export default defineEventHandler(async (event) => {
    try {
        const requestUrl = getRequestURL(event).pathname;
        if (!requestUrl.startsWith("/api")) return;

        const user = (await getUserSession(event)).user;
        const publicPaths = ["/api/auth/login/email", "/api/auth/login/guest", "/api/auth/2fa", "/api/status"];

        if (!user && !publicPaths.includes(requestUrl))
            throw new Error("This resource requires authentication.", { cause: { statusCode: 1401 } });
    } catch (error: any) {
        throw formatApiError(error);
    }
});