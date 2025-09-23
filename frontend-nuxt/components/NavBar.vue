<script lang="ts" setup>
import { useUserStore } from "@/stores/UserStore";
import { useNotificationStore } from "@/stores/NotificationStore";
import NotificationItem from "./NotificationItem.vue";
const userStore = useUserStore();
const notificationStore = useNotificationStore();
const router = useRouter();

// Reactive Data
const userDropdownIcon: Ref<HTMLElement | null> = useTemplateRef("userDropdownIcon");
const userDropdownMenu: Ref<HTMLDivElement | null> = useTemplateRef("userDropdownMenu");
const notificationDropdownMenu: Ref<HTMLDivElement | null> = useTemplateRef("notificationDropdownMenu");
const notificationLimit: Ref<number> = ref(5);
const notificationTooltip: Ref<HTMLDivElement | null> = useTemplateRef("notificationTooltip");
const userTooltip: Ref<HTMLDivElement | null> = useTemplateRef("userTooltip");

// Lifecycle Hooks
onMounted(() => {
    // Global Close Dropdown
    document.body.addEventListener("click", (event: MouseEvent): void => {
        const eventTarget: HTMLElement = event.target as HTMLElement;
        if (!eventTarget) return;

        const validClasses: Array<string> = ["notification-item", "visible", "shadow", "splitter", "user-dropdown-menu-header", "notification-click-item", "notification-action-button", "user-click-item", "click-item", "notification-dropdown-wrapper", "notification-dropdown-menu-header", "notification-dropdown-menu-footer"];
        const validClickItems: Array<String> = ["notification-click-item", "user-click-item"];
        let validClass = true;
        for (let i = 0; i < eventTarget.classList.length; i++) {
            if (!validClasses.includes(eventTarget.classList[i])) {
                validClass = false;
            } else validClass = true;

            if (eventTarget.classList[i] === validClickItems[0]) {
                closeUserDropdown();
            } else if (eventTarget.classList[i] === validClickItems[1]) closeNotificationDropdown();
        }

        if (!validClass) {
            closeUserDropdown();
            closeNotificationDropdown();
        }
    });
});

// Methods

/**
 * Open or close the user dropdown.
 */
function toggleUserDropdown(): void {
    if (userDropdownIcon.value && userDropdownIcon.value.classList.contains("rotated")) {
        closeUserDropdown();
    } else {
        if (userDropdownIcon.value) userDropdownIcon.value.classList.add("rotated");
        if (userDropdownMenu.value) userDropdownMenu.value.classList.add("visible");
        if (userTooltip.value) userTooltip.value.classList.add("hidden");
    }
}
/**
 * Open or close the notification dropdown.
 */
function toggleNotificationDropdown(): void {
    if (notificationDropdownMenu.value && notificationDropdownMenu.value.classList.contains("visible")) {
        closeNotificationDropdown();
    } else if (notificationDropdownMenu.value && notificationTooltip.value) {
        notificationDropdownMenu.value.classList.add("visible");
        notificationTooltip.value.classList.add("hidden");
    }
}
/**
 * Closes the user dropdown, regardless of current state.
 */
function closeUserDropdown(): void {
    if (userDropdownIcon.value) userDropdownIcon.value.classList.remove("rotated");
    if (userDropdownMenu.value) userDropdownMenu.value.classList.remove("visible");
    if (userTooltip.value) userTooltip.value.classList.remove("hidden");
}
/**
 * Closes the notification dropdown, regardless of current state.
 */
function closeNotificationDropdown(): void {
    if (notificationDropdownMenu.value) notificationDropdownMenu.value.classList.remove("visible");
    if (notificationTooltip.value) notificationTooltip.value.classList.remove("hidden");
}
/**
 * Clear store and redirect to homepage.
 */
function signOut() {
    userStore.signOut();
    router.push("/");
}
</script>

<template>
    <nav>
        <!-- TODO: #4 -->
        <div class="searchbar-container navbar-pill">
            <i class="fa-regular fa-magnifying-glass" @click="($refs['searchBar'] as HTMLInputElement).focus()"></i>
            <input ref="searchBar" placeholder="Search" type="text">
        </div>
        <!-- TODO: #5 -->
        <section class="nav-right flex">
            <div class="notification-pill-container">
                <div class="navbar-pill notification-pill">
                    <i v-if="notificationStore.unreadNotifications.length === 0"
                        :style="`color: var(--color-${notificationStore.highestPriority})`"
                        class="fa-regular fa-envelope"></i>
                    <i v-else :style="`color: var(--color-${notificationStore.highestPriority})`"
                        class="fa-regular fa-envelope-dot"></i>
                    <button class="click-item notification-click-item" title="Notifications" type="button"
                        @click="toggleNotificationDropdown()"></button>
                    <div ref="notificationTooltip" class="tooltip-item notification-pill-tooltip">
                        <span class="tooltip-arrow"></span>
                        <p>Notifications</p>
                    </div>
                </div>
                <div ref="notificationDropdownMenu" class="notification-dropdown-menu shadow dropdown-menu">
                    <section v-if="notificationStore.notifications.length === 0"
                        class="notification-dropdown-menu-empty flex-col">
                        <strong>Notification Center</strong>
                        <small>You are all caught up!</small>
                        <span class="splitter"></span>
                        <p>Nothing new to display at the moment.</p>
                    </section>
                    <div v-else class="notification-dropdown-wrapper flex-col">
                        <section class="notification-dropdown-menu-header flex-col">
                            <strong>Notification Center</strong>
                            <small>Most recent notifications.</small>
                            <span class="splitter"></span>
                        </section>
                        <NotificationItem
                            v-for="notification in notificationStore.notifications.slice(0, notificationLimit)"
                            :key="notification.ticket" :message="notification">
                        </NotificationItem>
                        <section class="notification-dropdown-menu-footer flex-col">
                            <span class="splitter"></span>
                            <NuxtLink class="notification-dropdown-menu-footer-link flex" to="/panel/notifications">
                                <p>See all ({{ notificationStore.notifications.length }})</p>
                                <i class="fa-regular fa-arrow-right"></i>
                            </NuxtLink>
                        </section>
                    </div>
                </div>
            </div>
            <div class="user-pill-container">
                <div class="navbar-pill user-pill">
                    <img :src="userStore.user.imageUrl" alt="Profile Picture">
                    <p>{{ userStore.user.firstName }} {{ userStore.user.lastName }}</p>
                    <i ref="userDropdownIcon" class="fa-regular fa-angle-down user-dropdown-icon"></i>
                    <button class="click-item user-click-item" title="Quick Access" type="button"
                        @click="toggleUserDropdown()"></button>
                </div>
                <div ref="userDropdownMenu" class="user-dropdown-menu shadow dropdown-menu">
                    <section class="user-dropdown-menu-header flex-col">
                        <strong>Quick Access</strong>
                        <small>Handy resources & links.</small>
                        <span class="splitter"></span>
                    </section>
                    <NuxtLink class="user-dropdown-item" to="/panel/dashboard">Home</NuxtLink>
                    <NuxtLink class="user-dropdown-item" to="/panel/preferences">Preferences</NuxtLink>
                    <span class="splitter"></span>
                    <button class="sign-out-button user-dropdown-item" title="Sign Out" type="button"
                        @click="signOut()">
                        <p>Sign Out</p>
                        <i class="fa-regular fa-arrow-right-from-bracket color-danger"></i>
                    </button>
                </div>
                <div ref="userTooltip" class="tooltip-item user-pill-tooltip relative-center">
                    <span class="tooltip-arrow"></span>
                    <p>Quick Access</p>
                </div>
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

.navbar-pill {
    display: flex;
    align-items: center;
    box-sizing: border-box;
    padding: 10px 15px;
    background-color: var(--color-fill);
    height: 40px;
    cursor: pointer;
}

.navbar-pill * {
    color: var(--color-text-light);
    user-select: none;
}

.navbar-pill i {
    width: 15px;
    aspect-ratio: 1 / 1;
}

.navbar-pill:hover {
    background-color: var(--color-fill-dark);
}

input {
    width: 100%;
}

/* Left */
.searchbar-container {
    gap: 15px;
    border-radius: var(--border-radius-high);
    width: 400px;
}

/* Right */
.nav-right {
    gap: 20px;
    padding-left: 20px;
}

.notification-icon {
    justify-content: center;
    border-radius: 50%;
    aspect-ratio: 1 / 1;
}

.notification-pill {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    position: relative;
    height: 40px;
    width: 40px;
    padding: 0;
}

.notification-pill:hover .notification-pill-tooltip {
    display: flex;
}

.user-pill-container {
    position: relative;
}

.user-pill-container:hover .user-pill-tooltip {
    display: flex;
}

.user-pill {
    display: flex;
    align-items: center;
    gap: 10px;
    width: fit-content;
    min-width: 200px;
    border-radius: var(--border-radius-high);
    position: relative;
}

.user-pill p {
    min-width: 150px;
}

img {
    height: 20px;
    aspect-ratio: 1 / 1;
    border-radius: 50%;
    object-fit: cover;
}

/* Gerneral Dropdown */
.user-click-item,
.notification-click-item {
    width: 100%;
    height: 100%;
    background-color: transparent;
    margin-left: -15px;
    position: absolute;
    z-index: 1;
}

.dropdown-menu {
    display: none;
    flex-direction: column;
    gap: 5px;
    position: absolute;
    height: fit-content;
    top: 50px;
    background-color: var(--color-fill);
    border-radius: var(--border-radius-low);
    user-select: none;
    z-index: 1;
    box-sizing: border-box;
    padding: 5px;
}

/* Notification Dropdown  */
.notification-click-item {
    margin: 0;
}

.notification-dropdown-menu {
    width: 410px;
    right: 280px;
    top: 75px;
}

.notification-dropdown-wrapper,
.notification-dropdown-menu-footer-link {
    gap: 10px;
}

.notification-dropdown-menu-footer-link {
    width: fit-content;
}

/* User Dropdown */
.user-dropdown-menu {
    width: 200px;
    right: 0;
    margin-right: 0;
}

.rotated {
    transform: rotate(180deg);
}

.visible {
    display: flex;
}

.user-dropdown-item {
    box-sizing: border-box;
    padding: 5px;
    border-radius: var(--border-radius-low);
}

.user-dropdown-item:hover {
    background-color: var(--color-fill-dark);
}

.sign-out-button {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.hidden {
    display: none !important;
}

/* Responsiveness */
@media (width <=1020px) {
    nav {
        flex-direction: column-reverse;
        gap: 10px;
    }

    .searchbar-container {
        width: 100%;
    }

    .nav-right {
        padding: 0;
        gap: 10px;
        width: 100%;
        justify-content: right;
    }
}

/* TODO #5 */
@media (width <=590px) {}
</style>