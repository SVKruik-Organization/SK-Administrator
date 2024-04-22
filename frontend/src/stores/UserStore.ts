import type { UserData } from "@/assets/customTypes";
import { defineStore } from "pinia";
import { useStorage } from "@vueuse/core";

export const useUserStore = defineStore("UserStore", {
    state: () => {
        return {
            user: useStorage('user', {} as UserData)
        }
    },
    actions: {
        setUser(userData: UserData): void {
            this.user = userData;
        }
    }
});