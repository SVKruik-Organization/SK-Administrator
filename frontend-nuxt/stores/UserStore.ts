import type { UserData } from "~/assets/customTypes";
import { defineStore } from "pinia";

export const useUserStore = defineStore("userStore", {
    state: () => {
        return {
            user: {} as UserData,
        };
    },
    persist: {
        storage: piniaPluginPersistedstate.localStorage(),
    },
    actions: {
        setUser(userData: UserData): void {
            this.user = userData;
        },
        async signOut(): Promise<void> {
            if (!this.isLoggedIn) return;
            const { clear: clearSession } = useUserSession();

            this.setUser({} as UserData);
            clearSession();
            navigateTo("/");
        },
    },
    getters: {
        isLoggedIn(): boolean {
            return !!this.user?.id;
        },
        isAdmin(): boolean {
            return true;
        },
    },
});
