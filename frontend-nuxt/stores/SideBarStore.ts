import { defineStore } from "pinia";
import type { Module, Profile, TopLink } from "~/assets/customTypes";

export const useSideBarStore = defineStore("SideBarStore", {
    state: () => {
        return {
            active_profile: 1 as number,
            profiles: [] as Array<Profile>,
            topItems: [] as Array<TopLink>,
            modules: [] as Array<Module>,
        }
    },
    persist: {
        storage: piniaPluginPersistedstate.localStorage(),
    },
    actions: {
        setSideBar(activeProfile: number, profiles: Array<Profile>, topItems: Array<TopLink>, modules: Array<Module>): void {
            this.active_profile = activeProfile;
            this.profiles = profiles;
            this.topItems = topItems;
            this.modules = modules;
        }
    },
    getters: {
        getActiveProfile(): Profile {
            return this.profiles.find(profile => profile.id === this.active_profile) || this.profiles[0];
        }
    }
});