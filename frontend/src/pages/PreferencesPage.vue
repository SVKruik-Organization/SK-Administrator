<script lang="ts">
import type { TabComponentItem } from '@/assets/customTypes';
import TabComponent from '@/components/TabComponent.vue';
import { defineComponent } from 'vue';

export default defineComponent({
    name: "PreferencesPage",
    components: {
        TabComponent
    },
    data() {
        return {
            "navigationTabs": [{
                "title": "Support",
                "route": "/support"
            }] as Array<TabComponentItem>
        }
    }
});
</script>

<template>
    <section class="flex-col">
        <div class="page-header">
            <TabComponent :base-route="'/panel/preferences'" :tabs="navigationTabs" :title="'Preferences'">
            </TabComponent>
            <span class="page-splitter"></span>
        </div>
        <RouterView></RouterView>
    </section>
</template>

<style scoped></style>
