<script lang="ts">
import type { TabComponentItem } from '@/assets/customTypes';
import TabComponent from '@/components/TabComponent.vue';
import { defineComponent } from 'vue';

export default defineComponent({
    name: "UsersPage",
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
                }] as Array<TabComponentItem>
        }
    }
});
</script>

<template>
    <section class="flex-col">
        <div class="page-header">
            <TabComponent :base-route="'/panel/operations/users'" :tabs="navigationTabs" :title="'Users'">
            </TabComponent>
            <span class="page-splitter"></span>
        </div>
        <RouterView></RouterView>
    </section>
</template>

<style scoped></style>
