import { formatApiError } from "~/utils/format";

export default defineEventHandler(async (event) => {
    try {
        const requestUrl = getRequestURL(event).pathname;
        if (!requestUrl.startsWith("/api")) return;
        const user = (await getUserSession(event)).user;

        // Public paths that do not require authentication
        const publicPaths = ["/api/status"];
        if (publicPaths.includes(requestUrl)) return;

        // Authentication routes (_auth for nuxt-auth-utils and auth for our custom auth)
        if (requestUrl.startsWith("/api/_auth/") || requestUrl.startsWith("/api/auth/")) return;

        if (!user) throw new Error("This resource requires authentication.", { cause: { statusCode: 1401 } });
    } catch (error: any) {
        throw formatApiError(error);
    }
});