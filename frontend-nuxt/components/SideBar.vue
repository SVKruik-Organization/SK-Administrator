<script lang="ts" setup>
import { useUserStore } from "@/stores/UserStore";
const userStore = useUserStore();

// Methods

/**
 * Opens the profile switcher dropdown.
 */
function toggleProfileSwitcher(): void {
    console.log("Profile Switcher clicked.");
}
</script>

<template>
    <nav class="flex-col">
        <img class="sidebar-logo-image no-select" src="/mesh_1.png" alt="Logo">
        <section class="sidebar-logo flex no-select">
            <h3>SK Administrator</h3>
        </section>
        <section class="user-information flex no-select" @click="toggleProfileSwitcher()">
            <img :src="userStore.user.imageUrl" alt="Profile Picture">
            <div class="user-information-text flex-col">
                <h3>{{ userStore.user.firstName }}</h3>
                <small>Work account</small>
            </div>
            <i class="fa-regular fa-angle-down profile-switcher"></i>
        </section>
        <section class="sidebar-content flex-col">
            <div class="sidebar-content-item flex-col">
                <menu>
                    <NuxtLink to="/panel/dashboard" v-slot="{ isActive }" class="sidebar-link sidebar-link-top">
                        <i :class="isActive ? 'fa-solid fa-chart-pie' : 'fa-regular fa-chart-pie'"></i>
                        <p>Dashboard</p>
                    </NuxtLink>
                    <NuxtLink to="/panel/users" v-slot="{ isActive }" class="sidebar-link sidebar-link-top">
                        <i :class="isActive ? 'fa-solid fa-user-group' : 'fa-regular fa-user-group'"></i>
                        <p>Users</p>
                    </NuxtLink>
                </menu>
            </div>
            <div class="sidebar-content-item flex-col">
                <NuxtLink to="/panel/guilds" v-slot="{ isActive }" class="flex">
                    <i :class="isActive ? 'fa-solid fa-users-gear' : 'fa-regular fa-users-gear'"></i>
                    <h4>Guilds</h4>
                </NuxtLink>
                <menu>
                    <NuxtLink to="/panel/guilds/overview" class="sidebar-link">
                        <p>Overview</p>
                    </NuxtLink>
                    <NuxtLink to="/panel/guilds/economy" class="sidebar-link">
                        <p>Economy</p>
                    </NuxtLink>
                    <NuxtLink to="/panel/guilds/tier" class="sidebar-link">
                        <p>Tier</p>
                    </NuxtLink>
                    <NuxtLink to="/panel/guilds/admins" class="sidebar-link">
                        <p>Admins</p>
                    </NuxtLink>
                    <NuxtLink to="/panel/guilds/blocked" class="sidebar-link">
                        <p>Blocked</p>
                    </NuxtLink>
                    <NuxtLink to="/panel/guilds/events" class="sidebar-link">
                        <p>Events</p>
                    </NuxtLink>
                </menu>
            </div>
            <div class="sidebar-content-item flex-col">
                <NuxtLink to="/panel/operators" v-slot="{ isActive }" class="flex">
                    <i :class="isActive ? 'fa-solid fa-user-group-crown' : 'fa-regular fa-user-group-crown'"></i>
                    <h4>Operators</h4>
                </NuxtLink>
                <menu>
                    <NuxtLink to="/panel/operators/teams" class="sidebar-link">
                        <p>Teams</p>
                    </NuxtLink>
                    <NuxtLink to="/panel/operators/owners" class="sidebar-link">
                        <p>Owners</p>
                    </NuxtLink>
                    <NuxtLink to="/panel/operators/members" class="sidebar-link">
                        <p>Members</p>
                    </NuxtLink>
                </menu>
            </div>
            <div class="sidebar-content-item flex-col">
                <NuxtLink to="/panel/status" v-slot="{ isActive }" class="flex">
                    <i :class="isActive ? 'fa-solid fa-satellite-dish' : 'fa-regular fa-satellite-dish'"></i>
                    <h4>Status</h4>
                </NuxtLink>
                <menu>
                    <NuxtLink to="/panel/status/sites" class="sidebar-link">
                        <p>Sites</p>
                    </NuxtLink>
                    <NuxtLink to="/panel/status/hardware" class="sidebar-link">
                        <p>Hardware</p>
                    </NuxtLink>
                </menu>
            </div>
            <div class="sidebar-content-item flex-col">
                <NuxtLink to="/panel/activity" v-slot="{ isActive }" class="flex">
                    <i :class="isActive ? 'fa-solid fa-wave-pulse' : 'fa-regular fa-wave-pulse'"></i>
                    <h4>Activity</h4>
                </NuxtLink>
                <menu>
                    <NuxtLink to="/panel/activity/logs" class="sidebar-link">
                        <p>Logs</p>
                    </NuxtLink>
                    <NuxtLink to="/panel/activity/purchases" class="sidebar-link">
                        <p>Purchases</p>
                    </NuxtLink>
                </menu>
            </div>
            <div class="sidebar-content-item flex-col">
                <NuxtLink to="/panel/records" v-slot="{ isActive }" class="flex">
                    <i :class="isActive ? 'fa-solid fa-folder-open' : 'fa-regular fa-folder-open'"></i>
                    <h4>Records</h4>
                </NuxtLink>
                <menu>
                    <NuxtLink to="/panel/records/tickets" class="sidebar-link">
                        <p>Tickets</p>
                    </NuxtLink>
                    <NuxtLink to="/panel/records/questions" class="sidebar-link">
                        <p>Questions</p>
                    </NuxtLink>
                    <NuxtLink to="/panel/records/reports" class="sidebar-link">
                        <p>Reports</p>
                    </NuxtLink>
                    <NuxtLink to="/panel/records/warnings" class="sidebar-link">
                        <p>Warnings</p>
                    </NuxtLink>
                </menu>
            </div>
            <NuxtLink to="/panel/preferences" v-slot="{ isActive }" class="sidebar-link" style="margin-bottom: 25px;">
                <i :class="isActive ? ' fa-solid fa-gear' : 'fa-regular fa-gear'"></i>
                <p>Preferences</p>
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
    z-index: 2;
}

/* Header */
.sidebar-logo-image {
    height: 400px;
    width: 1000px;
    position: absolute;
    opacity: 0.4;
    filter: blur(50px);
    pointer-events: none;
    left: -400px;
    top: -140px;
    z-index: 2;
    rotate: 30deg;
}

.carbon .sidebar-logo-image,
.monokai .sidebar-logo-image {
    opacity: 0.2;
}

.sidebar-logo {
    margin-left: 5px;
}

.user-information {
    gap: 15px;
    cursor: pointer;
    border-radius: 100px;
    padding: 8px;
    box-sizing: border-box;
    width: 95%;
    margin-left: -8px;
}

.user-information:hover {
    background-color: var(--color-background);
}

.user-information-text {
    gap: 0;
    align-items: baseline;
}

.user-information img {
    height: 50px;
    aspect-ratio: 1 / 1;
    border-radius: 50%;
    object-fit: cover;
}

.profile-switcher {
    opacity: 0;
}

.user-information:hover .profile-switcher {
    opacity: 1;
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

/* Active Link */
.sidebar-link.router-link-active {
    background-color: var(--color-fill-dark);
}

.sidebar-link-top.router-link-active {
    background-color: var(--color-background);
}
</style>