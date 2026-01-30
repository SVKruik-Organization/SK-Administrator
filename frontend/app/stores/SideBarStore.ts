import { createTicket } from "@svkruik/sk-platform-formatters";
import { defineStore } from "pinia";
import { Languages, PromptType, type Module, type PopupItem, type Profile, type ProfileData, type TopLink } from "@/assets/customTypes";
import { useFetchProfile } from "@/utils/fetch/user/useFetchProfile";

export const useSideBarStore = defineStore("SideBarStore", {
    state: () => {
        return {
            profileData: null as ProfileData | null,
        }
    },
    persist: {
        storage: piniaPluginPersistedstate.localStorage(),
    },
    actions: {
        /**
         * Loads the sidebar modules.
         * @param force Force reload of the modules even if they are already loaded.
         */
        async loadModules(force: boolean = false): Promise<void> {
            if (!this.profileData?.profiles.length || force) await this.switchProfile(null);
        },
        /**
         * Sets the sidebar state.
         * @param data The profile data to set in the sidebar.
         */
        setSideBar(data: ProfileData): void {
            this.profileData = data;
            document.documentElement.setAttribute("lang", this.language);
        },
        /**
         * Switches the active user profile.
         * @param profileId The ID of the profile to switch to. Use 0 to switch to the most recently used profile.
         */
        async switchProfile(profileId: string | null): Promise<void> {
            try {
                this.setSideBar(await useFetchProfile(profileId));
            } catch (error: any) {
                const { $event } = useNuxtApp();
                $event("popup", {
                    id: createTicket(4),
                    type: PromptType.danger,
                    message: error.message,
                    duration: 3,
                } as PopupItem);
            }
        },
        /**
         * Clears the sidebar state.
         */
        clear(): void {
            this.profileData = null;
        }
    },
    getters: {
        activeProfile(): Profile | null {
            if (!this.profileData) return null;
            return this.profileData.profiles.find(profile => profile.id === this.profileData?.activeProfileId) || null;
        },
        inactiveProfiles(): Array<Profile> {
            if (!this.profileData) return [];
            return this.profileData.profiles.filter(profile => profile.id !== this.profileData?.activeProfileId) || [];
        },
        activeProfileId(): string | null {
            return this.profileData?.activeProfileId || null;
        },
        firstItemUrl(): string {
            return this.profileData?.firstItemUrl || "/";
        },
        profiles(): Array<Profile> {
            return this.profileData?.profiles || [];
        },
        topLinks(): Array<TopLink> {
            return this.profileData?.topLinks || [];
        },
        modules(): Array<Module> {
            return this.profileData?.modules || [];
        },
        language(): Languages {
            return this.profileData?.language as Languages || Languages.EN;
        },
    }
});