<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useOnClickOutside } from '@/composables/useOnClickOutside';
import { logout } from '@/routes/authentication';
import { Auth } from '@/types';
import { index as homeIndex } from '@/routes/panel';
import { index as preferencesIndex } from '@/routes/panel/preferences';
import { notifications as notificationsIndex } from '@/routes/panel';
import NotificationItem from '@/components/ui/NotificationItem.vue';
import type { NotificationItem as NotificationItemType } from '@/types/custom';

const page = usePage();
const auth = computed(() => page.props.auth as Auth);
const searchBar = ref<HTMLInputElement | null>(null);
const notifications = ref<Array<NotificationItemType>>([]);
const unreadNotifications = ref<Array<NotificationItemType>>([]);
const availableNotifications = ref<Array<NotificationItemType>>([]);

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

function signOut() {
    quickMenuDropdownVisible.value = false;
    logout.form.post();
}

function toggleNotificationDropdownMenu() {
    notificationDropdownMenuVisible.value = !notificationDropdownMenuVisible.value;
    if (notificationDropdownMenuVisible.value) {
        quickMenuDropdownVisible.value = false;
    }
}

function toggleQuickMenuDropdownMenu() {
    quickMenuDropdownVisible.value = !quickMenuDropdownVisible.value;
    if (quickMenuDropdownVisible.value) {
        notificationDropdownMenuVisible.value = false;
    }
}
</script>

<template>
    <nav
        class="flex flex-col md:flex-row md:items-center justify-between box-border p-4 gap-4 border-b border-gray-200">
        <button class="flex items-center gap-2 cursor-pointer bg-gray-100 hover:bg-gray-200 rounded-full px-4 py-2"
            @click="searchBar?.focus()" type="button">
            <i class="fa-regular fa-magnifying-glass"></i>
            <input ref="searchBar" placeholder="Search" type="text">
        </button>
        <section class="flex items-center gap-4">
            <!-- Notifications -->
            <div ref="notificationDropdownRef" class="relative">
                <button
                    class="flex items-center justify-center gap-2 cursor-pointer bg-gray-100 hover:bg-gray-200 rounded-full h-10 w-10"
                    @click="toggleNotificationDropdownMenu()" title="Show Notifications" type="button">
                    <i v-if="unreadNotifications.length === 0" class="fa-regular fa-envelope"></i>
                    <i v-else class="fa-regular fa-envelope-dot"></i>
                </button>
                <menu v-if="notificationDropdownMenuVisible"
                    class="flex flex-col gap-1 absolute top-12 right-0 rounded-lg shadow-lg bg-gray-50 p-2 z-10 w-96">
                    <p class="font-medium">Notification Center</p>
                    <template v-if="notifications.length === 0">
                        <section class="flex flex-col">
                            <p class="text-sm text-gray-500">You are all caught up!</p>
                        </section>
                    </template>
                    <template v-else>
                        <section class="flex flex-col">
                            <p class="text-sm text-gray-500">Most recent notifications.</p>
                        </section>
                        <hr class="border-gray-200">
                        <NotificationItem v-for="notification in availableNotifications" :key="notification.id"
                            :notification="notification">
                        </NotificationItem>
                        <hr class="border-gray-200">
                        <Link :href="notificationsIndex.url()" class="flex items-center gap-2 justify-between"
                            title="See all notifications">
                            <p class="text-sm text-gray-500">See all notifications ({{ notifications.length }})</p>
                            <i class="fa-regular fa-arrow-right"></i>
                        </Link>
                    </template>
                </menu>
            </div>
            <!-- Quick Menu -->
            <div ref="quickMenuDropdownRef" class="relative">
                <button
                    class="flex items-center justify-center gap-2 cursor-pointer bg-gray-100 hover:bg-gray-200 rounded-full h-10 w-10"
                    @click="toggleQuickMenuDropdownMenu()" title="Show Quick Menu" type="button">
                    <i class="fa-regular fa-ellipsis-vertical"></i>
                </button>
                <menu v-if="quickMenuDropdownVisible"
                    class="flex flex-col gap-1 absolute top-12 right-0 rounded-lg shadow-lg bg-gray-50 p-2 z-10 w-48">
                    <p class="font-medium">Quick Access</p>
                    <hr class="border-gray-200">
                    <Link :href="homeIndex.url()" @click="toggleQuickMenuDropdownMenu()">Home</Link>
                    <Link :href="preferencesIndex.url()" @click="toggleQuickMenuDropdownMenu()">Preferences</Link>
                    <hr class="border-gray-200">
                    <button @click="signOut" title="Sign Out" type="button"
                        class="flex items-center gap-2 justify-between cursor-pointer">
                        <p>Sign Out</p>
                        <i class="fa-regular fa-arrow-right-from-bracket text-red-600"></i>
                    </button>
                </menu>
            </div>
        </section>
    </nav>
</template>
