/**
 * Routing middleware for protected pages.
 * Use `definePageMeta({ middleware: "auth"})` to register this middleware.
 */
export default defineNuxtRouteMiddleware(async () => {
    const { loggedIn } = useUserSession();
    if (!loggedIn.value) return navigateTo("/login");
});