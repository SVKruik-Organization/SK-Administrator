<script lang="ts" setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useOnClickOutside } from '@/composables/useOnClickOutside';
import { Auth } from '@/types';
import { switchMethod } from '@/routes/user/profile';
import type { LoadedUserProfile } from '@/types/custom';

// Props
const props = withDefaults(
    defineProps<{
        isOpen?: boolean;
    }>(),
    {
        isOpen: false,
    },
);

// Emitters
const emit = defineEmits<{
    (e: 'close-sidebar'): void;
}>();

// Reactive data
const page = usePage();
const auth = computed(() => page.props.auth as Auth);
const currentUrl = computed(() => page.url);
const activeProfile = computed(() => auth.value.active_profile as LoadedUserProfile);
const inactiveProfiles = computed(() =>
    auth.value.profiles.filter((profile) => profile.id !== auth.value.active_profile.id),
);

// Dropdown visibility
const isProfileSwitcherOpen = ref(false);
const profileDropdownRef = ref<HTMLElement | null>(null);

useOnClickOutside(profileDropdownRef, () => {
    isProfileSwitcherOpen.value = false;
});

// Methods

/**
 * Toggles the visibility of the profile switcher menu.
 */
function toggleProfileSwitcher(): void {
    isProfileSwitcherOpen.value = !isProfileSwitcherOpen.value;
}

/**
 * Closes the sidebar.
 */
function closeSidebar(): void {
    emit('close-sidebar');
}

/**
 * Switches the active user profile.
 * @param profileId The ID of the profile to switch to.
 */
function switchProfile(profileId: string): void {
    router.visit(switchMethod.url(profileId), {
        method: 'put',
        preserveState: true,
        onFinish: () => {
            isProfileSwitcherOpen.value = false;
            closeSidebar();
        },
        onSuccess: () => router.reload({ only: ['auth'] }),
    });
}

/**
 * Checks if a link is active.
 * @param href The href of the link to check.
 * @returns True if the link is active, false otherwise.
 */
function getLinkStatus(href: string): boolean {
    return currentUrl.value.startsWith(href);
}
</script>

<template>
    <nav :class="[props.isOpen ? 'translate-x-0' : '-translate-x-full']"
        class="flex flex-col h-screen bg-sky-50 overflow-hidden fixed inset-y-0 left-0 z-30 w-64 transform transition-transform duration-300 md:static md:w-3xs md:translate-x-0">
        <div ref="profileDropdownRef" class="relative p-2 border-b border-sky-200">
            <!-- Profile switcher button -->
            <button
                class="group flex items-center gap-2 cursor-pointer hover:bg-sky-100 rounded-4xl relative w-full p-2 h-14"
                type="button" :class="{ 'active bg-sky-100 rounded-b-none shadow-lg': isProfileSwitcherOpen }"
                @click="toggleProfileSwitcher()">
                <div class="relative">
                    <img src="/temp_pfp.jpg" class="aspect-square object-cover h-12 rounded-full">
                    <span :class="isProfileSwitcherOpen ? 'border-sky-100' : 'border-sky-50'"
                        class="h-4 w-4 bg-emerald-600 rounded-full absolute -top-0.5 -right-0.5 border-2 group-hover:border-sky-100"></span>
                </div>
                <div class="flex flex-col text-left">
                    <h3 class="font-medium">{{ auth.user.full_name }}</h3>
                    <small>
                        {{ activeProfile.name[activeProfile.language] }}
                    </small>
                </div>
                <i class="fa-regular fa-angle-down ml-auto" :class="{ 'rotate-180': isProfileSwitcherOpen }"></i>

                <!-- Profile switcher menu -->
                <menu v-if="auth.profiles.length > 1 && isProfileSwitcherOpen"
                    class="flex flex-col absolute top-14 right-0 rounded-b-xl shadow-lg bg-sky-100 p-2 z-10">
                    <button v-for="profile in inactiveProfiles" :key="profile.id"
                        class="flex flex-col p-2 rounded-lg hover:bg-sky-50 text-left cursor-pointer" type="button"
                        @click="switchProfile(profile.id)">
                        <strong>{{ profile.name[activeProfile.language] }}</strong>
                        <small>{{ profile.description[activeProfile.language] }}</small>
                    </button>
                </menu>
            </button>
        </div>
        <section class="flex flex-col h-full overflow-y-auto overflow-x-hidden pl-4 pt-4 gap-8">
            <!-- Top module items -->
            <div class="flex flex-col gap-4">
                <menu>
                    <Link v-for="item in auth.top_module_items" :key="item.id" :href="item.url"
                        :class="{ 'bg-sky-100': getLinkStatus(item.url) }"
                        class="flex items-center gap-2 h-10 rounded-full px-4 box-border w-11/12 hover:bg-sky-100"
                        @click="closeSidebar()">
                        <i :class="getLinkStatus(item.url) ? `fa-solid ${item.icon}` : `fa-regular ${item.icon}`"></i>
                        <p>{{ item.name![activeProfile.language] }}</p>
                    </Link>
                </menu>
            </div>

            <!-- Modules -->
            <div class="flex flex-col gap-4 last:mb-8" v-for="module in activeProfile.modules" :key="module.id">
                <Link :href="module.url" class="flex items-center gap-2" @click="closeSidebar()">
                    <i :class="getLinkStatus(module.url) ? `fa-solid ${module.icon}` : `fa-regular ${module.icon}`"></i>
                    <h4 class="font-medium">{{ module.name![activeProfile.language] }}</h4>
                </Link>
                <menu class="flex flex-col gap-2">
                    <Link v-for="item in module.items" :key="item.id" :href="item.url"
                        :class="{ 'bg-sky-100': getLinkStatus(item.url) }"
                        class="flex items-center gap-2 h-10 rounded-full px-4 box-border w-11/12 hover:bg-sky-100"
                        @click="closeSidebar()">
                        <p>{{ item.name[activeProfile.language] }}</p>
                    </Link>
                </menu>
            </div>
        </section>
    </nav>
</template>
