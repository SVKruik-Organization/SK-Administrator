<script lang="ts" setup>
import { Languages, PromptType, type PopupItem } from "@/assets/customTypes";
import { getImageUrl } from "@/utils/image";
import { normalizeUrl } from "@/utils/format";
import { createTicket } from "@svkruik/sk-platform-formatters";
import { useSideBarStore } from "@/stores/SideBarStore";
const sideBarStore = useSideBarStore();
const userSession = useUserSession();
const { $event, $listen } = useNuxtApp();

// Lifecycle Hooks
onMounted(async () => {
    await sideBarStore.loadModules();
    navigateTo(sideBarStore.firstItemUrl);
});

// Localizations
const translations: { [key: string]: { [lang in Languages]: string } } = {
    "error": {
        [Languages.EN]: "Something went wrong on our end. Please try again later.",
        [Languages.NL]: "Er is iets misgegaan. Probeer het later opnieuw.",
    },
    "click_switch_profile": {
        [Languages.EN]: "Click to switch user profile.",
        [Languages.NL]: "Klik om van gebruikersprofiel te wisselen.",
    },
    "profile_picture": {
        [Languages.EN]: "Profile Picture",
        [Languages.NL]: "Profielfoto",
    },
    "active_profile": {
        [Languages.EN]: "Active Profile",
        [Languages.NL]: "Actief Profiel",
    },
    "preferences": {
        [Languages.EN]: "Preferences",
        [Languages.NL]: "Voorkeuren",
    },
}

// Reactive Data
const isProfileSwitcherOpen: Ref<boolean> = ref(false);
const activeProfile: Ref<string> = ref(sideBarStore.activeProfile?.name || "");

// Methods

/**
 * Switches the active user profile.
 * @param profileId The ID of the profile to switch to.
 */
async function switchProfile(profileId: number): Promise<void> {
    try {
        await sideBarStore.switchProfile(profileId);
        activeProfile.value = sideBarStore.activeProfile?.name || "";
        isProfileSwitcherOpen.value = false;
        navigateTo(sideBarStore.firstItemUrl);
    } catch (error: any) {
        $event("popup", {
            id: createTicket(4),
            type: PromptType.danger,
            message: error.message || getTranslation("error"),
            duration: 3,
        } as PopupItem);
    }
}

/**
 * Opens or closes the profile switcher menu.
 * Also emits an event to close the navbar.
 */
function toggleProfileSwitcher(): void {
    if (sideBarStore.profiles.length <= 1) return;
    isProfileSwitcherOpen.value = !isProfileSwitcherOpen.value;
    $event("close-navbar");
}

/**
 * Retrieves the translation for a given key based on the current language.
 * @param key The key to translate.
 * @returns The translated string.
 */
function getTranslation(key: string): string {
    return translations[key]?.[sideBarStore.language] || key;
}

// Emitters
$listen("close-sidebar", () => isProfileSwitcherOpen.value = false);
</script>

<template>
    <div class="overlay" v-if="isProfileSwitcherOpen" @click="isProfileSwitcherOpen = false"></div>
    <nav class="flex-col">
        <img class="sidebar-logo-image no-select" src="/mesh_1.png" alt="Logo">
        <button class="sidebar-logo flex" @click="navigateTo(sideBarStore.firstItemUrl)" type="button"
            title="SK Administrator">
            <h3>SK Administrator</h3>
        </button>
        <button class="user-information flex no-select" type="button" :class="{ 'active': isProfileSwitcherOpen }"
            @click="toggleProfileSwitcher()" :title="getTranslation('click_switch_profile')">
            <img :src="getImageUrl(userSession.user.value)" :alt="getTranslation('profile_picture')"
                :title="getTranslation('profile_picture')">
            <div class="user-information-text flex-col">
                <h3>{{ userSession.user.value?.firstName }}</h3>
                <small :title="getTranslation('active_profile') + ': ' + activeProfile">
                    {{ activeProfile }}
                </small>
            </div>
            <i class="fa-regular fa-angle-down profile-switcher" :class="{ 'active': isProfileSwitcherOpen }"></i>
            <menu v-if="sideBarStore.profiles.length > 1 && isProfileSwitcherOpen" class="flex-col">
                <button v-for="profile in sideBarStore.inactiveProfiles" :key="profile.id"
                    class="flex-col profile-switcher-item" type="button" @click="switchProfile(profile.id)">
                    <strong>{{ profile.name }}</strong>
                    <small>{{ profile.description }}</small>
                </button>
            </menu>
        </button>
        <section class="sidebar-content flex-col">
            <div class="sidebar-content-item flex-col">
                <menu>
                    <NuxtLink v-for="item, key in sideBarStore.topLinks" :key="key"
                        :to="`/panel/${normalizeUrl(item.name)}`" v-slot="{ isActive }"
                        class="sidebar-link sidebar-link-top">
                        <i :class="isActive ? `fa-solid ${item.icon}` : `fa-regular ${item.icon}`"></i>
                        <p>{{ item.name[sideBarStore.language] }}</p>
                    </NuxtLink>
                </menu>
            </div>
            <div class="sidebar-content-item flex-col" v-for="module in sideBarStore.modules" :key="module.name.en">
                <NuxtLink :to="`/panel/${normalizeUrl(module.name)}`" v-slot="{ isActive }" class="flex">
                    <i :class="isActive ? `fa-solid ${module.icon}` : `fa-regular ${module.icon}`"></i>
                    <h4>{{ module.name[sideBarStore.language] }}</h4>
                </NuxtLink>
                <menu>
                    <NuxtLink v-for="item, key in module.links" :key="key"
                        :to="`/panel/${normalizeUrl(module.name.en + '/' + item.en)}`" class="sidebar-link">
                        <p>{{ item[sideBarStore.language] }}</p>
                    </NuxtLink>
                </menu>
            </div>
            <NuxtLink to="/panel/preferences" v-slot="{ isActive }" class="sidebar-link sidebar-link-bottom">
                <i :class="isActive ? ' fa-solid fa-gear' : 'fa-regular fa-gear'"></i>
                <p>{{ getTranslation('preferences') }}</p>
            </NuxtLink>
        </section>
    </nav>
</template>

<style scoped>
nav {
    height: 100vh;
    gap: 20px;
    width: 250px;
    box-sizing: border-box;
    padding: 25px 0 0 25px;
    background-color: var(--color-fill);
    position: relative;
    overflow-x: hidden;
}

nav section {
    z-index: 1;
}

.sidebar-logo-image {
    height: 400px;
    width: 1000px;
    position: absolute;
    opacity: 0.4;
    filter: blur(50px);
    pointer-events: none;
    left: -400px;
    top: -140px;
    rotate: 30deg;
}

.carbon .sidebar-logo-image,
.monokai .sidebar-logo-image {
    opacity: 0.2;
}

.sidebar-logo {
    margin-left: 5px;
    z-index: 1;
}

.sidebar-logo h3 {
    background: url(/mesh_2.png);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    background-repeat: no-repeat;
    background-position: center;
    background-size: 110%;
    font-weight: bolder;
    filter: brightness(0.8);
}

.user-information {
    gap: 15px;
    cursor: pointer;
    border-radius: 100px;
    padding: 8px;
    box-sizing: border-box;
    width: 215px;
    margin-left: -8px;
    z-index: 3;
    position: relative;
    border: 1px solid transparent;
}

.user-information-text {
    gap: 0;
    align-items: baseline;
    z-index: 1;
}

.user-information img {
    height: 50px;
    aspect-ratio: 1 / 1;
    border-radius: 50%;
    object-fit: cover;
    z-index: 1;
}

.profile-switcher {
    opacity: 0;
    margin-left: auto;
    margin-right: 5px;
    z-index: 1;
}

.user-information:hover {
    background-color: var(--color-background);
}

.user-information:hover .profile-switcher {
    opacity: 1;
}

.user-information menu {
    position: absolute;
    width: 215px;
    min-height: 110px;
    top: 33px;
    left: -1px;
    border-radius: 0 0 var(--border-radius-high) var(--border-radius-high);
    padding: 8px;
    box-sizing: border-box;
    background-color: var(--color-background);
    gap: 10px;
    border-top: none;
}

.profile-switcher-item:first-child {
    margin-top: 30px;
}

.profile-switcher-item:last-child {
    margin-bottom: 10px;
}

.profile-switcher-item {
    text-align: left;
    position: relative;
    gap: 0;
}

/* Content */
.sidebar-content,
.sidebar-content-item {
    gap: 0;
}

.sidebar-content {
    gap: 35px;
    overflow-y: auto;
    height: 100%;
}

.sidebar-content-item {
    gap: 15px;
}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: 15px;
    height: 40px;
    border-radius: var(--border-radius-high);
    box-sizing: border-box;
    padding: 10px;
    width: 90%;
}

.sidebar-link i {
    width: 15px;
    aspect-ratio: 1 / 1;
}

.sidebar-link-bottom {
    margin-bottom: 25px;
    margin-top: auto;
}

/* Active Link */
.sidebar-link.router-link-active {
    background-color: var(--color-fill-dark);
}

.sidebar-link-top.router-link-active {
    background-color: var(--color-background);
}

button.active {
    background-color: var(--color-background);
}

i.active {
    rotate: 180deg;
    opacity: 1;
}
</style>