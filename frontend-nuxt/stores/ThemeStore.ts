import { UIThemes } from "@/assets/customTypes";
import { defineStore } from "pinia";
import { useStorage } from "@vueuse/core";

export const useThemeStore = defineStore("ThemeStore", {
    state: () => {
        return {
            theme: useStorage("theme", "default" as keyof typeof UIThemes)
        }
    },
    persist: {
        storage: piniaPluginPersistedstate.localStorage(),
    },
    actions: {
        setTheme(newTheme: keyof typeof UIThemes): void {
            document.documentElement.className = UIThemes[newTheme];
            this.theme = newTheme;
        },
        loadTheme(): void {
            document.documentElement.className = UIThemes[this.theme as keyof typeof UIThemes];
        }
    }
});