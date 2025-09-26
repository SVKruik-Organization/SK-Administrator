<script lang="ts" setup>
import type { TabComponentItem } from "@/assets/customTypes";
import TabComponent from "@/components/TabComponent.vue";

// Reactive Data
const navigationTabs = ref<Array<TabComponentItem>>([
    {
        title: "Profile",
        route: "/profile"
    },
    {
        title: "Security",
        route: "/security"
    },
    {
        title: "Notifications",
        route: "/notifications"
    }]);
</script>

<template>
    <section class="flex-col content-view">
        <div class="page-header">
            <TabComponent :base-route="'/panel/preferences'" :tabs="navigationTabs" :title="'Preferences'">
            </TabComponent>
            <span class="page-splitter"></span>
        </div>
        <NuxtPage></NuxtPage>
    </section>
</template>

<style scoped></style>
