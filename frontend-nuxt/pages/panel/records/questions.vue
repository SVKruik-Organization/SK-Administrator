<script lang="ts" setup>
import type { TabComponentItem } from "@/assets/customTypes";
import TabComponent from "@/components/TabComponent.vue";

// Reactive Data
const navigationTabs = ref<Array<TabComponentItem>>([
    {
        title: "Pending",
        route: "/pending"
    },
    {
        title: "Active",
        route: "/active"
    },
    {
        title: "Answered",
        route: "/answered"
    }]);
</script>

<template>
    <section class="flex-col">
        <div class="page-header">
            <TabComponent :base-route="'/panel/records/questions'" :tabs="navigationTabs" :title="'Questions'">
            </TabComponent>
            <span class="page-splitter"></span>
        </div>
        <NuxtPage></NuxtPage>
    </section>
</template>

<style scoped></style>
