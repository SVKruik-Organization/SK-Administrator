<script lang="ts" setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Auth } from '@/types';
import { index as preferencesIndex } from '@/routes/panel/preferences';
import { switchMethod } from '@/routes/user/profile';
import type { UserProfile } from '@/types/custom';

const page = usePage();
const auth = computed(() => page.props.auth as Auth);
const inactiveProfiles = computed(() => auth.value.profiles.filter(profile => profile.id !== auth.value.active_profile.id));

const currentUrl = computed(() => page.url);
const isLinkActive = (href: string): boolean => {
    return currentUrl.value.startsWith(href);
};

const isProfileSwitcherOpen = ref(false);
const activeProfile = ref<UserProfile | null>(null);

const toggleProfileSwitcher = () => {
    isProfileSwitcherOpen.value = !isProfileSwitcherOpen.value;
};

const switchProfile = (profileId: string) => {
    switchMethod.form.put(profileId);
    activeProfile.value = auth.value.profiles.find(profile => profile.id === profileId) || null;
}
</script>

<template>
    <div class="" v-if="isProfileSwitcherOpen" @click="isProfileSwitcherOpen = false"></div>
    <nav class="flex flex-col relative h-screen w-3xs px-4 py-8 bg-blue-100/30">
        <!-- <img class="sidebar-logo-image" src="/img/mesh_1.png" alt="Logo"> -->
        <a :href="auth.first_item_url" class="" title="SK Administrator">
            <h3 class="font-bold">SK Administrator</h3>
        </a>
        <button class="flex" type="button" :class="{ 'active': isProfileSwitcherOpen }"
            @click="toggleProfileSwitcher()">
            <img :src="auth.user.id">
            <div class="flex flex-col" v-if="activeProfile">
                <h3>{{ auth.user.full_name }}</h3>
                <small>
                    Active profile name
                </small>
            </div>
            <i class="fa-regular fa-angle-down" :class="{ 'active': isProfileSwitcherOpen }"></i>
            <menu v-if="auth.profiles.length > 1 && isProfileSwitcherOpen" class="flex flex-col">
                <button v-for="profile in inactiveProfiles" :key="profile.id" class="flex flex-col" type="button"
                    @click="switchProfile(profile.id)">
                    <strong>{{ profile.name[auth.active_profile.language] }}</strong>
                    <small>{{ profile.description[auth.active_profile.language] }}</small>
                </button>
            </menu>
        </button>
        <section class="flex flex-col h-full overflow-y-auto gap-8">
            <div class="flex flex-col gap-4">
                <!-- Top items -->
                <menu>
                    <Link v-for="item in auth.top_module_items" :key="item.id" :href="item.url"
                        :class="{ 'bg-blue-200/20': isLinkActive(item.url) }"
                        class="flex items-center gap-2 h-10 rounded-full px-4 box-border w-full">
                        <i :class="isLinkActive(item.url) ? `fa-solid ${item.icon}` : `fa-regular ${item.icon}`"></i>
                        <p>{{ item.name![auth.active_profile.language] }}</p>
                    </Link>
                </menu>
            </div>
            <!-- Modules -->
            <div class="flex flex-col gap-4" v-for="module in auth.active_profile.modules" :key="module.id">
                <Link :href="module.url" class="flex items-center gap-2">
                    <i :class="isLinkActive(module.url) ? `fa-solid ${module.icon}` : `fa-regular ${module.icon}`"></i>
                    <h4 class="font-medium">{{ module.name![auth.active_profile.language] }}</h4>
                </Link>
                <menu class="flex flex-col gap-2">
                    <Link v-for="item in module.items" :key="item.id" :href="item.url"
                        :class="{ 'bg-blue-200/20': isLinkActive(item.url) }"
                        class="flex items-center gap-2 h-10 rounded-full px-4 box-border w-full">
                        <p>{{ item.name[auth.active_profile.language] }}</p>
                    </Link>
                </menu>
            </div>
            <Link :href="preferencesIndex.url()"
                class="flex items-center gap-2 h-10 rounded-lg px-4 box-border w-full mt-auto">
                <i class="fa-regular fa-gear"></i>
                <p>Preferences</p>
            </Link>
        </section>
    </nav>
</template>

<style scoped>
.sidebar-logo-image {
    height: 400px;
    width: 1000px;
    position: absolute;
    opacity: 0.4;
    filter: blur(50px);
    pointer-events: none;
    left: -400px;
    top: -140px;
    rotate: 30deg;
}
</style>