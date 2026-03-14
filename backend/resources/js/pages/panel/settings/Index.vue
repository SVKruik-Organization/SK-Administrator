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
    cta: {
        label: string;
        url: string;
    } | null;
}>();
</script>

<template>
    <TabBar>
        <TabItem name="modules" label="Modules">
            <Modules :table="table" :cta="cta" />
        </TabItem>
    </TabBar>
</template>