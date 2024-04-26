<script lang="ts">
import type { TabComponentItem } from '@/assets/customTypes';
import TabComponent from '@/components/TabComponent.vue';
import { defineComponent } from 'vue';

export default defineComponent({
    name: "TeamsPage",
    components: {
        TabComponent
    },
    data() {
        return {
            "navigationTabs": [{
                "title": "Overview",
                "route": "/overview"
            },
                {
                    "title": "Features",
                    "route": "/features"
                }] as Array<TabComponentItem>
        }
    }
});
</script>

<template>
    <section class="flex-col">
        <div class="page-header">
            <TabComponent :base-route="'/panel/operations/teams'" :tabs="navigationTabs" :title="'Teams'">
            </TabComponent>
            <span class="page-splitter"></span>
        </div>
        <RouterView></RouterView>
    </section>
</template>

<style scoped></style>
