<script lang="ts" setup>
import { useUserStore } from "~/stores/UserStore";
import { useNotificationStore } from "~/stores/NotificationStore";
import { getImageUrl } from "~/utils/image";
import type { NotificationItem } from "~/assets/customTypes";
const userStore = useUserStore();
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
    const data: NotificationItem = JSON.parse(newValue as string);
    if (!(data satisfies NotificationItem) || getNotificationExclusions().includes(data.type) || data.is_silent) return;

    notificationStore.notifications.push(data);
}, { immediate: true });

// Methods

/**
 * Toggles the visibility of a dropdown menu.
 * Also closes any other open dropdowns and emits an event to close the sidebar.
 * @param menu The menu to toggle.
 */
function toggleDropdown(menu: HTMLMenuElement | null) {
    $event("close-sidebar");

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

    isDropdownOpen.value = true;
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
            <input ref="searchBar" placeholder="Search" type="text">
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
                            <strong>Notification Center</strong>
                            <small>You are all caught up!</small>
                        </section>
                    </template>
                    <template v-else>
                        <section class="flex-col">
                            <strong>Notification Center</strong>
                            <small>Most recent notifications.</small>
                        </section>
                        <span class="splitter"></span>
                        <NotificationItem
                            v-for="notification in notificationStore.notifications.slice(0, notificationLimit)"
                            :key="notification.ticket" :message="notification">
                        </NotificationItem>
                        <span class="splitter"></span>
                        <NuxtLink to="/panel/notifications" @click="toggleDropdown(null)" class="flex-between"
                            title="View All Notifications">
                            <p>See all ({{ notificationStore.notifications.length }})</p>
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
                        <img :src="getImageUrl(userStore.user)" alt="Profile Picture">
                        <span class="status-indicator" :class="{ 'status-online': wsStatus === 'OPEN' }"></span>
                    </div>
                    <p>{{ userStore.user?.firstName }} {{ userStore.user?.lastName }}</p>
                    <i class="fa-regular fa-angle-down"></i>
                </button>
                <menu ref="userDropdownMenu" class="flex-col shadow">
                    <section class="flex-col">
                        <strong>Quick Access</strong>
                        <small>Handy resources & links.</small>
                        <span class="splitter"></span>
                    </section>
                    <NuxtLink to="/panel">Home</NuxtLink>
                    <NuxtLink to="/panel/preferences">Preferences</NuxtLink>
                    <span class="splitter"></span>
                    <button @click="userStore.signOut()" title="Sign Out" type="button" class="flex-between">
                        <p>Sign Out</p>
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

.navbar-item:hover .status-indicator,
.navbar-item:focus-within .status-indicator {
    border-color: var(--color-fill-dark);
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

.user-menu-parent menu {
    width: 220px;
}

/* User Profile Picture */
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

.status-online {
    background-color: var(--color-success);
}

.user-menu-parent button > i {
    margin-left: auto;
}

/* TODO #5 */
@media (width <=590px) {}
</style>