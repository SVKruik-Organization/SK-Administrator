<script lang="ts" setup>
import { useUserStore } from "@/stores/UserStore";
import { getImageUrl } from "~/utils/image";
const userStore = useUserStore();
const sideBarStore = useSideBarStore();

// Methods

/**
 * Opens the profile switcher dropdown.
 */
function toggleProfileSwitcher(): void {
    console.log("Profile Switcher clicked.");
}

/**
 * Normalizes a URL by replacing spaces with hyphens and converting to lowercase.
 * @param url - The URL to normalize.
 * @returns The normalized URL.
 */
function normalizeUrl(url: string): string {
    return url.replaceAll(" ", "-").toLowerCase();
}
</script>

<template>
    <nav class="flex-col">
        <img class="sidebar-logo-image no-select" src="/mesh_1.png" alt="Logo">
        <section class="sidebar-logo flex no-select">
            <h3>SK Administrator</h3>
        </section>
        <section class="user-information flex no-select" @click="toggleProfileSwitcher()">
            <img :src="getImageUrl(userStore.user)" alt="Profile Picture">
            <div class="user-information-text flex-col">
                <h3>{{ userStore.user?.firstName }}</h3>
                <small>{{ sideBarStore.getActiveProfile?.name }}</small>
            </div>
            <i class="fa-regular fa-angle-down profile-switcher"></i>
        </section>
        <section class="sidebar-content flex-col">
            <div class="sidebar-content-item flex-col">
                <menu>
                    <NuxtLink v-for="item, key in sideBarStore.topItems" :key="key"
                        :to="`/panel/${normalizeUrl(item.name)}`" v-slot="{ isActive }"
                        class="sidebar-link sidebar-link-top">
                        <i :class="isActive ? `fa-solid ${item.icon}` : `fa-regular ${item.icon}`"></i>
                        <p>{{ item.name }}</p>
                    </NuxtLink>
                </menu>
            </div>
            <div class="sidebar-content-item flex-col" v-for="module in sideBarStore.modules" :key="module.name">
                <NuxtLink :to="`/panel/${normalizeUrl(module.name)}`" v-slot="{ isActive }" class="flex">
                    <i :class="isActive ? `fa-solid ${module.icon}` : `fa-regular ${module.icon}`"></i>
                    <h4>{{ module.name }}</h4>
                </NuxtLink>
                <menu>
                    <NuxtLink v-for="item, key in module.links" :key="key"
                        :to="`/panel/${normalizeUrl(module.name + '/' + item)}`" class="sidebar-link">
                        <p>{{ item }}</p>
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