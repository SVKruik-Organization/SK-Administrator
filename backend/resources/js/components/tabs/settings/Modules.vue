<script lang="ts" setup>
import ObjectTable from '@/components/ui/ObjectTable.vue';
import type { ObjectTableData } from '@/types/custom';

const props = defineProps<{
    table: ObjectTableData;
}>();
</script>

<template>
    <p class="text-sm text-gray-500">
        Modules settings.
    </p>
    <ObjectTable
        :rows="table.data"
        :columns="table.columns"
        :page="table.page"
        :per-page="table.perPage"
        :total="table.total"
        :has-more="table.hasMore"
        only="modulesTable"
    />
</template>