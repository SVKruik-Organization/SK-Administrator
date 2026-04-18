<script lang="ts" setup>
import { ref, watch } from 'vue';
import draggable from 'vuedraggable';
import { router } from '@inertiajs/vue3';
import Button from '@/components/ui/form/Button.vue';

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
    class: '',
});

const sortedRows = ref<Array<Record<string, any>>>(props.rows);

watch(sortedRows, (newVal) => {
    router.put(props.reorderUrl, {
        moduleItems: newVal,
    });
}, {
    deep: true,
});
</script>

<template>
    <table v-if="rows.length" class="ordered-table w-full border-separate border-spacing-0" :class="class">
        <thead class="bg-theme">
            <tr>
                <th class="w-8 rounded-tl-md"></th>
                <th v-for="(name, key) in columns" :key="key" class="text-left px-2 py-1">
                    {{ name }}
                </th>
                <th class="w-8 rounded-tr-md"></th>
            </tr>
        </thead>

        <draggable v-model="sortedRows" item-key="id" tag="tbody" handle=".drag-handle">
            <template #item="{ element }">
                <tr class="hover:bg-theme-dark">
                    <td class="p-2 cursor-move drag-handle border-l-2 border-b-2 border-theme">
                        <i class="fa-regular fa-bars"></i>
                    </td>

                    <td v-for="(key, index) in Object.keys(columns)" :key="key"
                        class="text-left p-2 border-b-2 border-theme"
                        :class="{ 'border-l-2 border-theme': index > 0 }">
                        {{ element[key] }}
                    </td>

                    <td class="border-r-2 border-b-2 border-theme">
                        <Button :href="element.url" icon="fa-regular fa-eye" theme="transparent" />
                    </td>
                </tr>
            </template>
        </draggable>
    </table>
    <p v-else class="text-center text-gray-500 py-8">Geen items gevonden.</p>
</template>

<style scoped>
@reference '../../../css/app.css';

.ordered-table tbody tr:last-child>td:first-child {
    @apply rounded-bl-md;
}

.ordered-table tbody tr:last-child>td:last-child {
    @apply rounded-br-md;
}
</style>