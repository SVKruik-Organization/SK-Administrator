<script setup lang="ts">
import { Languages, PromptTypes, type PopupItem } from '~/assets/customTypes';
import { useFetchValidateToken } from '~/utils/fetch/auth/useFetchValidateToken';
const userSession = useUserSession();
const sideBarStore = useSideBarStore();
const { $event } = useNuxtApp();

const query = useRoute().query;
const token = query.token as string | undefined;
if (!token) navigateTo("/");

onMounted(async () => {
    try {
        await useFetchValidateToken(token!);
        if (userSession.loggedIn) navigateTo("/panel");
    } catch (error: any) {
        $event("popup", {
            id: createTicket(4),
            type: PromptTypes.danger,
            message: error.message || getTranslation("error"),
            duration: 3,
        } as PopupItem);
    }
});


// Localizations
const translations: { [key: string]: { [lang in Languages]: string } } = {
    "error": {
        [Languages.EN]: "Something went wrong on our end. Please try again later.",
        [Languages.NL]: "Er is iets misgegaan. Probeer het later opnieuw.",
    },
}

// Methods

/**
 * Retrieves the translation for a given key based on the current language.
 * @param key The key to translate.
 * @returns The translated string.
 */
function getTranslation(key: string): string {
    return translations[key]?.[sideBarStore.getLanguage] || key;
}
</script>

<template>
    Working...
</template>