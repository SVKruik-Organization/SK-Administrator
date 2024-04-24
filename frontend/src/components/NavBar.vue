<script lang="ts">
import { defineComponent } from 'vue';
import { RouterLink } from 'vue-router';
import { useUserStore } from '@/stores/UserStore';
import { useNotificationStore } from '@/stores/NotificationStore';
import type { UserData } from '@/assets/customTypes';
import NotificationItem from './NotificationItem.vue';

export default defineComponent({
    name: "NavbarComponent",
    data() {
        return {
            "userDropdownIcon": null as unknown as HTMLElement,
            "userDropdownMenu": null as unknown as HTMLDivElement,
            "notificationDropdownMenu": null as unknown as HTMLDivElement,
            "notificationLimit": 5,
            "notificationTooltip": null as unknown as HTMLDivElement,
            "userTooltip": null as unknown as HTMLDivElement
        }
    },
    components: {
        NotificationItem
    },
    setup() {
        return {
            userStore: useUserStore(),
            notificationStore: useNotificationStore()
        }
    },
    methods: {
        /**
         * Open or close the user dropdown.
         */
        toggleUserDropdown(): void {
            if (this.userDropdownIcon && this.userDropdownIcon.classList.contains("rotated")) {
                this.closeUserDropdown();
            } else {
                if (this.userDropdownIcon) this.userDropdownIcon.classList.add("rotated");
                if (this.userDropdownMenu) this.userDropdownMenu.classList.add("visible");
                if (this.userTooltip) this.userTooltip.classList.add("hidden");
            }
        },
        /**
         * Open or close the notification dropdown.
         */
        toggleNotificationDropdown(): void {
            if (this.notificationDropdownMenu && this.notificationDropdownMenu.classList.contains("visible")) {
                this.closeNotificationDropdown();
            } else if (this.notificationDropdownMenu && this.notificationTooltip) {
                this.notificationDropdownMenu.classList.add("visible");
                this.notificationTooltip.classList.add("hidden");
            }
        },
        /**
         * Closes the user dropdown, regardless of current state.
         */
        closeUserDropdown(): void {
            if (this.userDropdownIcon) this.userDropdownIcon.classList.remove("rotated");
            if (this.userDropdownMenu) this.userDropdownMenu.classList.remove("visible");
            if (this.userTooltip) this.userTooltip.classList.remove("hidden");
        },
        /**
         * Closes the notification dropdown, regardless of current state.
         */
        closeNotificationDropdown(): void {
            if (this.notificationDropdownMenu) this.notificationDropdownMenu.classList.remove("visible");
            if (this.notificationTooltip) this.notificationTooltip.classList.remove("hidden");
        },
        /**
         * Clear store and redirect to homepage.
         */
        signOut() {
            this.userStore.setUser({} as UserData);
            this.$router.push("/");
        }
    },
    mounted() {
        // Set Dropdown Components
        this.userDropdownIcon = this.$refs["userDropdownIcon"] as HTMLElement;
        this.userDropdownMenu = this.$refs["userDropdownMenu"] as HTMLDivElement;
        this.notificationDropdownMenu = this.$refs["notificationDropdownMenu"] as HTMLDivElement;
        this.notificationTooltip = this.$refs["notificationTooltip"] as HTMLDivElement;
        this.userTooltip = this.$refs["userTooltip"] as HTMLDivElement;

        // Global Close Dropdown
        document.body.addEventListener("click", (event: MouseEvent): void => {
            const eventTarget: HTMLElement = event.target as HTMLElement;
            if (!eventTarget) return;

            const validClasses: Array<string> = ["notification-item", "visible", "shadow", "splitter", "notification-click-item", "user-dropdown-menu-header", "user-click-item", "notification-dropdown-wrapper", "notification-dropdown-menu-header", "notification-dropdown-menu-footer"];
            const validClickItems: Array<String> = ["notification-click-item", "user-click-item"];
            let validClass = true;
            for (let i = 0; i < eventTarget.classList.length; i++) {
                if (!validClasses.includes(eventTarget.classList[i])) {
                    validClass = false;
                } else validClass = true;

                if (eventTarget.classList[i] === validClickItems[0]) {
                    this.closeUserDropdown();
                } else if (eventTarget.classList[i] === validClickItems[1]) this.closeNotificationDropdown();
            }

            if (!validClass) {
                this.closeUserDropdown();
                this.closeNotificationDropdown();
            }
        });
    },
});
</script>

<template>
    <nav>
        <div class="searchbar-container navbar-pill">
            <i class="fa-regular fa-magnifying-glass" @click="($refs['searchBar'] as HTMLInputElement).focus()"></i>
            <input placeholder="Search" type="text" ref="searchBar">
        </div>
        <section class="nav-right flex">
            <div class="notification-pill-container">
                <div class="navbar-pill notification-pill">
                    <i v-if="notificationStore.unreadNotifications.length === 0" class="fa-regular fa-circle-check"
                        :style="`color: var(--color-${notificationStore.highestPriority})`"></i>
                    <i v-else class="fa-regular fa-circle-exclamation"
                        :style="`color: var(--color-${notificationStore.highestPriority})`"></i>
                    <button title="Notifications" type="button" class="click-item notification-click-item"
                        @click="toggleNotificationDropdown()"></button>
                    <div class="tooltip-item notification-pill-tooltip" ref="notificationTooltip">
                        <span class="tooltip-arrow"></span>
                        <p>Notifications</p>
                    </div>
                </div>
                <div ref="notificationDropdownMenu" class="notification-dropdown-menu shadow dropdown-menu">
                    <section v-if="notificationStore.notifications.length === 0"
                        class="notification-dropdown-menu-empty">
                        <strong>Notification Center</strong>
                        <span class="splitter"></span>
                        <p>Nothing new to display at the moment.</p>
                    </section>
                    <div v-else class="notification-dropdown-wrapper">
                        <section class="notification-dropdown-menu-header">
                            <strong>Notification Center</strong>
                            <small>Last {{ notificationStore.notifications.length >= 6 ? 5 :
                                notificationStore.notifications.length }}
                                {{ notificationStore.notifications.length > 1 ? "notifications" : "notification"
                                }}.</small>
                            <span class="splitter"></span>
                        </section>
                        <NotificationItem
                            v-for="notification in notificationStore.notifications.slice(0, notificationLimit)"
                            :id="notification.ticket" :ticket="notification.ticket" :type="notification.type"
                            :message="notification.message" :unread="notification.unread" :source="notification.source"
                            :date="new Date(notification.date)">
                        </NotificationItem>
                        <section class="notification-dropdown-menu-footer">
                            <span class="splitter"></span>
                            <RouterLink to="/panel/dashboard/notifications"
                                class="notification-dropdown-menu-footer-link flex">
                                <p>See all ({{ notificationStore.notifications.length }})</p>
                                <i class="fa-regular fa-arrow-right"></i>
                            </RouterLink>
                        </section>
                    </div>
                </div>
            </div>
            <div class="user-pill-container">
                <div class="navbar-pill user-pill">
                    <img :src="userStore.user.imageUrl" alt="Profile Picture">
                    <p>{{ userStore.user.firstName }} {{ userStore.user.lastName }}</p>
                    <i ref="userDropdownIcon" class="fa-regular fa-angle-down user-dropdown-icon"></i>
                    <button title="Quick Access" type="button" class="click-item user-click-item"
                        @click="toggleUserDropdown()"></button>
                </div>
                <div ref="userDropdownMenu" class="user-dropdown-menu shadow dropdown-menu">
                    <section class="user-dropdown-menu-header">
                        <strong>Quick Access</strong>
                        <small>Handy resources & links.</small>
                        <span class="splitter"></span>
                    </section>
                    <RouterLink class="user-dropdown-item" to="/">Home</RouterLink>
                    <RouterLink class="user-dropdown-item" to="/panel/preferences">Preferences</RouterLink>
                    <RouterLink class="user-dropdown-item" to="/panel/preferences/support">Support</RouterLink>
                    <span class="splitter"></span>
                    <button title="Sign Out" type="button" class="sign-out-button user-dropdown-item"
                        @click="signOut()">
                        <p>Sign Out</p>
                        <i class="fa-regular fa-arrow-right-from-bracket color-danger"></i>
                    </button>
                </div>
                <div class="tooltip-item user-pill-tooltip relative-center" ref="userTooltip">
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
    height: fit-content;
    border-bottom: 2px solid var(--color-border);
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
    width: fit-content;
    right: 280px;
    top: 75px;
}

.notification-dropdown-wrapper,
.notification-dropdown-menu-empty,
.notification-dropdown-menu-header,
.notification-dropdown-menu-footer,
.user-dropdown-menu-header {
    display: flex;
    flex-direction: column;
    gap: 5px;
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
    right: 0px;
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