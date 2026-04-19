<script lang="ts" setup>
import ObjectTable from '@/components/ui/ObjectTable.vue';
import type { ObjectTableData } from '@/types/custom';

const props = defineProps<{
    table: ObjectTableData;
}>();
</script>

<template>
    <div class="flex flex-col gap-4">
        <ObjectTable :rows="table.data" :columns="table.columns" :page="table.page" :per-page="table.perPage"
            :total-records="table.totalRecords" :total-pages="table.totalPages" :has-more="table.hasMore"
            only="table" />
    </div>
</template>