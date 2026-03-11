<script lang="ts" setup>
import { ref } from 'vue';
import SideBar from '@/components/SideBar.vue';
import NavBar from '@/components/NavBar.vue';

// Reactive data
const isSidebarOpen = ref(false);

/**
 * Closes the sidebar.
 */
function closeSidebar(): void {
    isSidebarOpen.value = false;
}

/**
 * Toggles the visibility of the sidebar.
 */
function toggleSidebar(): void {
    isSidebarOpen.value = !isSidebarOpen.value;
}
</script>

<template>
    <main class="flex relative">
        <!-- Mobile overlay behind the sidebar -->
        <div v-if="isSidebarOpen" class="fixed inset-0 bg-black/40 z-20 md:hidden" @click="closeSidebar()"></div>

        <SideBar :is-open="isSidebarOpen" @close-sidebar="closeSidebar()" />

        <section class="flex flex-col flex-1">
            <NavBar :is-sidebar-open="isSidebarOpen" @toggle-sidebar="toggleSidebar()" />
            <div class="p-4">
                <slot />
            </div>
        </section>
    </main>
</template>