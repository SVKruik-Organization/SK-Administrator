<script lang="ts" setup>
import { getImageUrl } from "@/utils/image";
import { Languages } from "@/assets/customTypes";
import type { NotificationItem } from "@/assets/customTypes";
import { useSideBarStore } from "@/stores/SideBarStore";
import { useNotificationStore } from "@/stores/NotificationStore";
import { useThemeStore } from "@/stores/ThemeStore";
import { getNotificationExclusions } from "@/utils/settings";
const sideBarStore = useSideBarStore();
const userSession = useUserSession();
const notificationStore = useNotificationStore();
const { $event, $listen } = useNuxtApp();
const { data: wsStream, status: wsStatus } = useAppWebSocket();

// Reactive Data
const userDropdownMenu: Ref<HTMLMenuElement | null> = useTemplateRef("userDropdownMenu");
const notificationDropdownMenu: Ref<HTMLMenuElement | null> = useTemplateRef("notificationDropdownMenu");
const searchBar: Ref<HTMLInputElement | null> = useTemplateRef("searchBar");
const notificationLimit: Ref<number> = ref(5);
const isDropdownOpen: Ref<boolean> = ref(false);

// Watchers
watch(() => wsStream.value, (newValue: any) => {
    if (!newValue) return;
    const notification: NotificationItem = JSON.parse(newValue as string);
    if (!(notification satisfies NotificationItem) || getNotificationExclusions().includes(notification.data.type) || notification.is_silent) return;

    notificationStore.notifications.push(notification);
}, { immediate: true });


// Localizations
const translations: { [key: string]: { [lang in Languages]: string } } = {
    "error": {
        [Languages.EN]: "Something went wrong on our end. Please try again later.",
        [Languages.NL]: "Er is iets misgegaan. Probeer het later opnieuw.",
    },
    "search": {
        [Languages.EN]: "Search",
        [Languages.NL]: "Zoeken",
    },
    "notification_center": {
        [Languages.EN]: "Notification Center",
        [Languages.NL]: "Meldingen Centrum",
    },
    "notifications_caught_up": {
        [Languages.EN]: "You are all caught up!",
        [Languages.NL]: "Je bent helemaal bij!",
    },
    "notifications_recent": {
        [Languages.EN]: "Most recent notifications.",
        [Languages.NL]: "Meest recente meldingen.",
    },
    "notifications_all": {
        [Languages.EN]: "See all",
        [Languages.NL]: "Bekijk alles",
    },
    "quick_access": {
        [Languages.EN]: "Quick Access",
        [Languages.NL]: "Snelle Toegang",
    },
    "profile_picture": {
        [Languages.EN]: "Profile Picture",
        [Languages.NL]: "Profielfoto",
    },
    "home": {
        [Languages.EN]: "Home",
        [Languages.NL]: "Home",
    },
    "preferences": {
        [Languages.EN]: "Preferences",
        [Languages.NL]: "Voorkeuren",
    },
    "sign_out": {
        [Languages.EN]: "Sign Out",
        [Languages.NL]: "Uitloggen",
    },
};

// Methods

/**
 * Toggles the visibility of a dropdown menu.
 * Also closes any other open dropdowns and emits an event to close the sidebar.
 * @param menu The menu to toggle.
 */
function toggleDropdown(menu: HTMLMenuElement | null) {

    if (menu === userDropdownMenu.value) {
        notificationDropdownMenu.value?.classList.remove("open");
        userDropdownMenu.value?.classList.toggle("open");
    } else if (menu === notificationDropdownMenu.value) {
        userDropdownMenu.value?.classList.remove("open");
        notificationDropdownMenu.value?.classList.toggle("open");
    } else {
        // Close all dropdowns if null is passed
        userDropdownMenu.value?.classList.remove("open");
        notificationDropdownMenu.value?.classList.remove("open");
        isDropdownOpen.value = false;
        return;
    }

    $event("close-sidebar");
    isDropdownOpen.value = true;
}

/**
 * Retrieves the translation for a given key based on the current language.
 * @param key The key to translate.
 * @returns The translated string.
 */
function getTranslation(key: string): string {
    return translations[key]?.[sideBarStore.language] || key;
}

/**
 * Signs the user out by clearing the session and relevant stores.
 */
async function signOut(): Promise<void> {
    notificationStore.clear();
    sideBarStore.clear();
    useThemeStore().clear();

    await userSession.clear();
    navigateTo("/");
}

// Emitters
$listen("close-navbar", () => toggleDropdown(null));
</script>

<template>
    <div class="overlay" v-if="isDropdownOpen" @click="isDropdownOpen = false; toggleDropdown(null)"></div>
    <nav>
        <!-- TODO: #4 -->
        <button class="flex navbar-item" @click="searchBar?.focus()" type="button">
            <i class="fa-regular fa-magnifying-glass"></i>
            <input ref="searchBar" :placeholder="getTranslation('search')" type="text">
        </button>
        <!-- TODO: #5 -->
        <section class="flex">
            <!-- Notifications -->
            <div class="menu-parent notification-parent">
                <button class="navbar-item navbar-item-small" @click="toggleDropdown(notificationDropdownMenu)"
                    title="Show Notifications" type="button">
                    <i v-if="notificationStore.unreadNotifications.length === 0"
                        :style="`color: var(--color-${notificationStore.highestPriority})`"
                        class="fa-regular fa-envelope"></i>
                    <i v-else :style="`color: var(--color-${notificationStore.highestPriority})`"
                        class="fa-regular fa-envelope-dot"></i>
                </button>
                <menu ref="notificationDropdownMenu" class="flex-col shadow">
                    <template v-if="notificationStore.notifications.length === 0">
                        <section class="flex-col">
                            <strong>{{ getTranslation('notifications_caught_up') }}</strong>
                            <small>{{ getTranslation('notifications_all') }}</small>
                        </section>
                    </template>
                    <template v-else>
                        <section class="flex-col">
                            <strong>{{ getTranslation('notifications_center') }}</strong>
                            <small>{{ getTranslation('notifications_recent') }}</small>
                        </section>
                        <span class="splitter"></span>
                        <NotificationItem
                            v-for="notification in notificationStore.notifications.slice(0, notificationLimit)"
                            :key="notification.id" :notification="notification">
                        </NotificationItem>
                        <span class="splitter"></span>
                        <NuxtLink to="/panel/notifications" @click="toggleDropdown(null)" class="flex-between"
                            :title="getTranslation('notifications_all')">
                            <p>{{ getTranslation('notifications_all') }} ({{ notificationStore.notifications.length }})
                            </p>
                            <i class="fa-regular fa-arrow-right"></i>
                        </NuxtLink>
                    </template>
                </menu>
            </div>
            <!-- User Menu -->
            <div class="menu-parent user-menu-parent">
                <button class="flex navbar-item" @click="toggleDropdown(userDropdownMenu)" title="Open User Menu"
                    type="button">
                    <div class="image-container">
                        <img :src="getImageUrl(userSession.user.value)" :alt="getTranslation('profile_picture')">
                        <span class="status-indicator" :class="{ 'status-online': wsStatus === 'OPEN' }"></span>
                    </div>
                    <p>{{ userSession.user.value?.fullName }}</p>
                    <i class="fa-regular fa-angle-down"></i>
                </button>
                <menu ref="userDropdownMenu" class="flex-col shadow">
                    <strong>{{ getTranslation('quick_access') }}</strong>
                    <span class="splitter"></span>
                    <NuxtLink to="/panel" @click="toggleDropdown(null)">{{ getTranslation('home') }}</NuxtLink>
                    <NuxtLink to="/panel/preferences" @click="toggleDropdown(null)">{{ getTranslation('preferences') }}
                    </NuxtLink>
                    <span class="splitter"></span>
                    <button @click="signOut" :title="getTranslation('sign_out')" type="button" class="flex-between">
                        <p>{{ getTranslation('sign_out') }}</p>
                        <i class="fa-regular fa-arrow-right-from-bracket color-danger"></i>
                    </button>
                </menu>
            </div>
        </section>
    </nav>
</template>

<style scoped>
nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-sizing: border-box;
    padding: 25px;
    border-bottom: 2px solid var(--color-border);
    flex: 1;
}

.navbar-item {
    background-color: var(--color-fill);
    cursor: pointer;
    box-sizing: border-box;
    padding: 10px;
    border-radius: 100px;
    height: 50px;
    min-width: 220px;
}

.navbar-item-small {
    min-width: unset;
    width: 50px;
    border-radius: 50%;
}

.navbar-item:hover,
.navbar-item:focus-within {
    background-color: var(--color-fill-dark);
}

/* Generic Menu */
.menu-parent {
    position: relative;
    z-index: 3;
}

.menu-parent menu {
    position: absolute;
    top: 60px;
    right: 0;
    width: 400px;
    max-height: 400px;
    background-color: var(--color-fill);
    display: none;
    box-sizing: border-box;
    padding: 5px;
    border-radius: var(--border-radius-low);
}

.menu-parent menu.open {
    display: flex;
}

/* Notification Menu */
.notification-parent button > i {
    font-size: 20px;
}

/* User Menu */
.user-menu-parent menu {
    width: 220px;
}

.user-menu-parent button > i {
    margin-left: auto;
}

.image-container {
    position: relative;
    height: 30px;
}

.image-container img {
    height: 100%;
    aspect-ratio: 1 / 1;
    border-radius: 50%;
    object-fit: cover;
}

.status-indicator {
    position: absolute;
    top: -3px;
    right: -3px;
    height: 7px;
    width: 7px;
    border-radius: 50%;
    border: 2px solid var(--color-fill);
    background-color: var(--color-danger);
}

.navbar-item:hover .status-indicator,
.navbar-item:focus-within .status-indicator {
    border-color: var(--color-fill-dark);
}

.status-online {
    background-color: var(--color-success);
}

/* TODO #5 */
@media (width <=590px) {}
</style>