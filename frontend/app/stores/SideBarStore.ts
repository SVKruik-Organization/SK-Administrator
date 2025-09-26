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
        setSideBar(activeProfileId: number, profiles: Array<Profile>, topItems: Array<TopLink>, modules: Array<Module>): void {
            this.active_profile_id = activeProfileId;
            this.profiles = profiles;
            this.topItems = topItems;
            this.modules = modules;
        },
        async switchProfile(profileId: number): Promise<boolean> {
            const response: ProfileData = await useFetchProfile(profileId);
            this.setSideBar(response.activeProfileId, response.profiles, response.topItems, response.modules);
            return true;
        },
        clear(): void {
            this.active_profile_id = 1;
            this.profiles = [];
            this.topItems = [];
            this.modules = [];
        }
    },
    getters: {
        getActiveProfile(): Profile | null {
            return this.profiles.find(profile => profile.id === this.active_profile_id) || null;
        },
        getInactiveProfiles(): Array<Profile> {
            return this.profiles.filter(profile => profile.id !== this.active_profile_id);
        }
    }
});