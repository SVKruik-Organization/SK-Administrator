<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useOnClickOutside } from '@/composables/useOnClickOutside';
import { logout } from '@/routes/authentication';
import { index as preferencesIndex } from '@/routes/panel/preferences';
import { notifications as notificationsIndex } from '@/routes/panel';
import NotificationItem from '@/components/ui/NotificationItem.vue';
import type { NotificationItem as NotificationItemType } from '@/types/custom';
import type { Auth } from '@/types';

// Props
const props = withDefaults(
    defineProps<{
        isSidebarOpen?: boolean;
    }>(),
    {
        isSidebarOpen: false,
    },
);

// Emitters
const emit = defineEmits<{
    (e: 'toggle-sidebar'): void;
}>();

// Reactive data
const page = usePage();
const auth = computed(() => page.props.auth as Auth);
const searchBar = ref<HTMLInputElement | null>(null);
const notifications = ref<Array<NotificationItemType>>([]);
const unreadNotifications = ref<Array<NotificationItemType>>([]);
const availableNotifications = ref<Array<NotificationItemType>>([]);

// Dropdown visibility
const notificationDropdownMenuVisible = ref(false);
const quickMenuDropdownVisible = ref(false);
const notificationDropdownRef = ref<HTMLElement | null>(null);
const quickMenuDropdownRef = ref<HTMLElement | null>(null);

useOnClickOutside(notificationDropdownRef, () => {
    notificationDropdownMenuVisible.value = false;
});
useOnClickOutside(quickMenuDropdownRef, () => {
    quickMenuDropdownVisible.value = false;
});

// Methods

/**
 * Signs the user out by visiting the logout route.
 */
function signOut(): void {
    router.visit(logout.url(), {
        method: 'post',
        preserveState: true,
        onSuccess: () => router.reload({ only: ['auth'] }),
    });
}

/**
 * Toggles the visibility of the notification dropdown menu.
 */
function toggleNotificationDropdownMenu(): void {
    notificationDropdownMenuVisible.value = !notificationDropdownMenuVisible.value;
    quickMenuDropdownVisible.value = false;
}

/**
 * Toggles the visibility of the quick menu dropdown menu.
 */
function toggleQuickMenuDropdownMenu(): void {
    quickMenuDropdownVisible.value = !quickMenuDropdownVisible.value;
    notificationDropdownMenuVisible.value = false;
}
</script>

<template>
    <nav
        class="flex items-center justify-between box-border p-4 gap-4 border-gray-200 fixed bottom-0 w-full md:static md:border-b">
        <!-- Mobile hamburger button -->
        <button
            class="flex items-center justify-center gap-2 cursor-pointer bg-gray-100 hover:bg-gray-200 rounded-full h-10 w-10 md:hidden"
            type="button" :aria-expanded="props.isSidebarOpen" aria-controls="panel-sidebar"
            @click="emit('toggle-sidebar')" title="Open the sidebar">
            <i class="fa-regular fa-bars"></i>
        </button>

        <!-- Search -->
        <button
            class="flex items-center gap-2 cursor-text bg-gray-100 hover:bg-gray-200 rounded-full px-4 py-2 flex-1 md:flex-none"
            @click="searchBar?.focus()" type="button">
            <i class="fa-regular fa-magnifying-glass"></i>
            <input ref="searchBar" placeholder="Search" type="text" class="outline-none">
        </button>

        <section class="flex items-center gap-2">
            <div ref="notificationDropdownRef" class="relative hidden md:block">
                <!-- Notification dropdown button -->
                <button
                    class="hidden items-center justify-center gap-2 cursor-pointer bg-gray-100 hover:bg-gray-200 rounded-full h-10 w-10 md:flex"
                    @click="toggleNotificationDropdownMenu()" title="Open the notification center" type="button">
                    <i v-if="unreadNotifications.length === 0" class="fa-regular fa-envelope"></i>
                    <i v-else class="fa-regular fa-envelope-dot"></i>
                </button>

                <!-- Notification dropdown menu -->
                <menu v-if="notificationDropdownMenuVisible"
                    class="flex flex-col absolute top-12 right-0 rounded-lg shadow-lg bg-gray-50 z-10 w-96">
                    <p class="font-medium p-2 pointer-events-none">Notification Center</p>
                    <hr class="border-gray-200">
                    <NotificationItem v-for="notification in availableNotifications" :key="notification.id"
                        v-if="availableNotifications.length" :notification="notification">
                    </NotificationItem>
                    <p class="text-sm text-gray-500 p-2" v-else>You are all caught up! Check back later.</p>
                    <hr class="border-gray-200">
                    <Link :href="notificationsIndex.url()"
                        class="flex items-center gap-2 justify-between px-2 py-1 hover:bg-gray-100"
                        title="See all notifications">
                        <p>See all notifications ({{ notifications.length }})</p>
                        <i class="fa-regular fa-arrow-right"></i>
                    </Link>
                </menu>
            </div>
            <div ref="quickMenuDropdownRef" class="relative">
                <!-- Quick menu button -->
                <button
                    class="flex items-center justify-center gap-2 cursor-pointer bg-gray-100 hover:bg-gray-200 rounded-full h-10 w-10"
                    @click="toggleQuickMenuDropdownMenu()" title="Show Quick Menu" type="button">
                    <i class="fa-regular fa-ellipsis-vertical"></i>
                </button>

                <!-- Quick menu dropdown menu -->
                <menu v-if="quickMenuDropdownVisible"
                    class="flex flex-col absolute bottom-12 md:top-12 md:bottom-auto right-0 rounded-lg shadow-lg bg-gray-50 z-10 w-48">
                    <p class="font-medium p-2 pointer-events-none">Quick Access</p>
                    <hr class="border-gray-200">
                    <Link :href="auth.first_item_url" @click="toggleQuickMenuDropdownMenu()"
                        class="px-2 py-1 hover:bg-gray-100" title="Go to your homepage">
                        Your Home
                    </Link>
                    <Link :href="preferencesIndex.url()" @click="toggleQuickMenuDropdownMenu()"
                        class="px-2 py-1 hover:bg-gray-100" title="Go to your preferences">
                        Preferences
                    </Link>
                    <Link :href="notificationsIndex.url()" @click="toggleQuickMenuDropdownMenu()"
                        class="px-2 py-1 hover:bg-gray-100 md:hidden" title="Go to your notifications">
                        Notifications
                    </Link>
                    <hr class="border-gray-200">
                    <button @click="signOut()" type="button" title="Sign out of your account"
                        class="flex items-center gap-2 p-2 justify-between cursor-pointer hover:bg-gray-100">
                        <p>Sign Out</p>
                        <i class="fa-regular fa-arrow-right-from-bracket text-red-600"></i>
                    </button>
                </menu>
            </div>
        </section>
    </nav>
</template>
