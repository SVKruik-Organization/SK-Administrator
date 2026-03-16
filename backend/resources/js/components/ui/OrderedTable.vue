<script lang="ts" setup>
import { ref, watch } from 'vue';
import draggable from 'vuedraggable';
import { Link, router } from '@inertiajs/vue3';

// Props
const props = withDefaults(defineProps<{
    rows: Array<Record<string, any>>;
    columns: Record<string, string>;
    totalRecords?: number;
    only?: string;
    class?: string;
    reorderUrl: string;
}>(), {
    rows: () => [],
    columns: () => ({}),
    totalRecords: 0,
    only: undefined,
    class: 'w-full',
});

const sortedRows = ref<Array<Record<string, any>>>(props.rows);

watch(sortedRows, (newVal) => {
    router.visit(props.reorderUrl, {
        method: 'put',
        data: {
            moduleItems: newVal,
        },
    });
}, {
    deep: true,
});
</script>

<template>
    <table class="w-full border-separate border-spacing-0">
        <thead class="bg-sky-100">
            <tr>
                <th class="w-8 rounded-tl-md"></th>
                <th v-for="(name, key) in columns" :key="key"
                class="text-left px-2 py-1">
                    {{ name }}
                </th>
                <th class="w-8 rounded-tr-md"></th>
            </tr>
        </thead>

        <draggable v-model="sortedRows" item-key="id" tag="tbody" handle=".drag-handle">
            <template #item="{ element }">
                <tr class="hover:bg-sky-50">
                    <td class="p-2 cursor-move drag-handle">
                        <i class="fa-regular fa-bars"></i>
                    </td>

                    <td v-for="(_name, key) in columns" :key="key" class="text-left p-2">
                        {{ element[key] }}
                    </td>
                    <td>
                        <Link :href="element.url">
                            <i class="fa-regular fa-eye"></i>
                        </Link>
                    </td>
                </tr>
            </template>
        </draggable>
    </table>
</template>