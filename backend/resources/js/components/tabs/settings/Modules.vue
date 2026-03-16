<script lang="ts" setup>
import ObjectTable from '@/components/ui/ObjectTable.vue';
import type { ObjectTableData } from '@/types/custom';
import Button from '@/components/ui/form/Button.vue';

const props = defineProps<{
    table: ObjectTableData;
    cta: {
        label: string;
        url: string;
    } | null;
}>();
</script>

<template>
    <div class="flex flex-col gap-4">
        <div class="flex justify-between items-start gap-6">
            <p class="text-gray-500">
                Overzicht van alle modules.
            </p>
            <Button v-if="cta" :label="cta.label" :url="cta.url" icon="fa-regular fa-plus" />
        </div>
        <ObjectTable :rows="table.data" :columns="table.columns" :page="table.page" :per-page="table.perPage"
            :total-records="table.totalRecords" :total-pages="table.totalPages" :has-more="table.hasMore"
            only="table" />
    </div>
</template>