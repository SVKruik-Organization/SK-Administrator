<script lang="ts" setup>
import PanelLayout from '@/layouts/PanelLayout.vue';
import TabBar from '@/components/ui/TabBar.vue';
import TabItem from '@/components/ui/TabItem.vue';
import Pending from '@/components/tabs/questions/Pending.vue';
import Active from '@/components/tabs/questions/Active.vue';
import Answered from '@/components/tabs/questions/Answered.vue';

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
        <TabItem name="Answered">
            <Answered />
        </TabItem>
    </TabBar>
</template>