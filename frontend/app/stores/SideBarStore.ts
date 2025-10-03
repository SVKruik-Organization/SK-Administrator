import { defineStore } from "pinia";
import { Languages, PromptTypes, type Module, type PopupItem, type Profile, type ProfileData, type TopLink } from "~/assets/customTypes";
import { useFetchProfile } from "~/utils/fetch/user/useFetchProfile";

export const useSideBarStore = defineStore("SideBarStore", {
    state: () => {
        return {
            activeProfileId: 1 as number,
            firstItemUrl: "/" as string,
            profiles: [] as Array<Profile>,
            topItems: [] as Array<TopLink>,
            modules: [] as Array<Module>,
            language: Languages.EN as Languages,
        } satisfies ProfileData
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
            if (!this.profiles.length || force) await this.switchProfile(0);
        },
        /**
         * Sets the sidebar state.
         * @param activeProfileId The ID of the active profile.
         * @param firstItemUrl The URL to navigate to on first login.
         * @param profiles The list of user profiles.
         * @param topItems The list of top-level items.
         * @param modules The list of modules.
         */
        setSideBar(data: ProfileData): void {
            this.activeProfileId = data.activeProfileId;
            this.firstItemUrl = data.firstItemUrl;
            this.profiles = data.profiles;
            this.topItems = data.topItems;
            this.modules = data.modules;
            this.language = data.language || Languages.EN;
            document.documentElement.setAttribute("lang", this.getLanguage);
        },
        /**
         * Switches the active user profile.
         * @param profileId The ID of the profile to switch to. Use 0 to switch to the most recently used profile.
         * @returns Status of the operation.
         */
        async switchProfile(profileId: number): Promise<boolean> {
            try {
                const response: ProfileData = await useFetchProfile(profileId);
                this.setSideBar(response);
                return true;
            } catch (error: any) {
                const { $event } = useNuxtApp();
                $event("popup", {
                    id: createTicket(4),
                    type: PromptTypes.danger,
                    message: error.message,
                    duration: 3,
                } as PopupItem);
                return false;
            }
        },
        /**
         * Clears the sidebar state.
         */
        clear(): void {
            this.activeProfileId = 1;
            this.profiles = [];
            this.topItems = [];
            this.modules = [];
        }
    },
    getters: {
        getActiveProfile(): Profile | null {
            return this.profiles.find(profile => profile.id === this.activeProfileId) || null;
        },
        getInactiveProfiles(): Array<Profile> {
            return this.profiles.filter(profile => profile.id !== this.activeProfileId);
        },
        getLanguage(): Languages {
            return this.language || Languages.EN;
        }
    }
});