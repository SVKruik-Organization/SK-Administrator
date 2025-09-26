import { UIThemes } from "@/assets/customTypes";
import { defineStore } from "pinia";

export const useThemeStore = defineStore("ThemeStore", {
    state: () => {
        return {
            theme: UIThemes.default
        }
    },
    persist: {
        storage: piniaPluginPersistedstate.localStorage(),
    },
    actions: {
        setTheme(newTheme: UIThemes): void {
            document.documentElement.className = UIThemes[newTheme];
            this.theme = newTheme;
        },
        loadTheme(): void {
            this.setTheme(this.theme);
        }
    }
});