import { Languages, type UserData } from "~/assets/customTypes";
import { defineStore } from "pinia";

export const useUserStore = defineStore("userStore", {
    state: () => {
        return {
            user: null as UserData | null,
        };
    },
    persist: {
        storage: piniaPluginPersistedstate.localStorage(),
    },
    actions: {
        setUser(userData: UserData | null): void {
            this.user = userData;
        },
        async signOut(): Promise<void> {
            if (!this.isLoggedIn) return;
            const { clear: clearSession } = useUserSession();

            // Clear other stores
            useNotificationStore().clear();
            useSideBarStore().clear();
            useThemeStore().clear();

            this.setUser(null);
            clearSession();
            navigateTo("/");
        },
    },
    getters: {
        isLoggedIn(): boolean {
            return !!this.user?.id;
        },
        getLanguage(): Languages {
            return this.user?.language ?? Languages.EN;
        }
    },
});
