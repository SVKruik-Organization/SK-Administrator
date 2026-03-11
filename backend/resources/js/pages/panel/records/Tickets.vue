<script lang="ts" setup>
import PanelLayout from '@/layouts/PanelLayout.vue';
import TabBar from '@/components/ui/TabBar.vue';
import TabItem from '@/components/ui/TabItem.vue';
import Pending from '@/components/tabs/tickets/Pending.vue';
import Active from '@/components/tabs/tickets/Active.vue';
import Closed from '@/components/tabs/tickets/Closed.vue';

defineOptions({
    layout: PanelLayout,
});
</script>

<template>
    <TabBar>
        <TabItem name="Pending">
            <Pending />
        </TabItem>
        <TabItem name="Active">
            <Active />
        </TabItem>
        <TabItem name="Closed">
            <Closed />
        </TabItem>
    </TabBar>
</template>