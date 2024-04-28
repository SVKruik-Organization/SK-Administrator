<script lang="ts">
import type { TabComponentItem } from '@/assets/customTypes';
import TabComponent from '@/components/TabComponent.vue';
import { defineComponent } from 'vue';

export default defineComponent({
    name: "AccountsPage",
    components: {
        TabComponent
    },
    data() {
        return {
            "navigationTabs": [{
                "title": "Members",
                "route": "/members"
            },
            {
                "title": "Team Owners",
                "route": "/owners"
            },
            {
                "title": "Users",
                "route": "/users"
            }] as Array<TabComponentItem>
        }
    }
});
</script>

<template>
    <section class="flex-col">
        <div class="page-header">
            <TabComponent :base-route="'/panel/operations/accounts'" :tabs="navigationTabs" :title="'Accounts'">
            </TabComponent>
            <span class="page-splitter"></span>
        </div>
        <RouterView></RouterView>
    </section>
</template>

<style scoped></style>
