import { defineStore } from "pinia";
import type { Module, Profile, ProfileData, TopLink } from "~/assets/customTypes";
import { useFetchProfile } from "~/utils/fetch/user/useFetchProfile";

export const useSideBarStore = defineStore("SideBarStore", {
    state: () => {
        return {
            active_profile_id: 1 as number,
            profiles: [] as Array<Profile>,
            topItems: [] as Array<TopLink>,
            modules: [] as Array<Module>,
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
            if (!this.profiles.length || force) await this.switchProfile(0);
        },
        /**
         * Sets the sidebar state.
         * @param activeProfileId The ID of the active profile.
         * @param profiles The list of user profiles.
         * @param topItems The list of top-level items.
         * @param modules The list of modules.
         */
        setSideBar(activeProfileId: number, profiles: Array<Profile>, topItems: Array<TopLink>, modules: Array<Module>): void {
            this.active_profile_id = activeProfileId;
            this.profiles = profiles;
            this.topItems = topItems;
            this.modules = modules;
        },
        /**
         * Switches the active user profile.
         * @param profileId The ID of the profile to switch to. Use 0 to switch to the most recently used profile.
         * @returns Status of the operation.
         */
        async switchProfile(profileId: number): Promise<boolean> {
            const response: ProfileData = await useFetchProfile(profileId);
            this.setSideBar(response.activeProfileId, response.profiles, response.topItems, response.modules);
            useUserStore().user!.language = response.language;
            return true;
        },
        /**
         * Clears the sidebar state.
         */
        clear(): void {
            this.active_profile_id = 1;
            this.profiles = [];
            this.topItems = [];
            this.modules = [];
        }
    },
    getters: {
        getActiveProfile(): Profile | null {
            console.log("Getting active profile");
            return this.profiles.find(profile => profile.id === this.active_profile_id) || null;
        },
        getInactiveProfiles(): Array<Profile> {
            console.log("Getting inactive profiles");
            return this.profiles.filter(profile => profile.id !== this.active_profile_id);
        }
    }
});