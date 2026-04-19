<script lang="ts" setup>
import PanelLayout from '@/layouts/PanelLayout.vue';
import TabBar from '@/components/ui/TabBar.vue';
import TabItem from '@/components/ui/TabItem.vue';
import Modules from '@/components/tabs/settings/Modules.vue';
import type { ObjectTableData } from '@/types/custom';

defineOptions({
    layout: PanelLayout,
});

const props = defineProps<{
    activeTab: string;
    table: ObjectTableData;
}>();
</script>

<template>
    <TabBar>
        <TabItem name="modules" label="Modules">
            <div class="flex flex-col gap-8">
                <div class="flex justify-between items-start gap-6">
                    <p class="text-gray-700">
                        Modules die gebruikers kunnen toevoegen aan hun profiel.
                    </p>
                </div>
                <Modules :table="table" />
            </div>
        </TabItem>
    </TabBar>
</template>