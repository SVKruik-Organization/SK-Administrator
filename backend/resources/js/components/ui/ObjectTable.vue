<script lang="ts" setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
const inertiaPage = usePage();

// Props
const props = defineProps<{
    rows: Array<Record<string, any>>;
    columns: Record<string, string>;
    page: number;
    perPage: number;
    total: number;
    hasMore: boolean;
    baseUrl?: string;
    only?: string;
}>();

// Computed properties
const hasPrevious = computed(() => props.page > 1);

// Methods

/**
 * Resolves the base URL for the object table.
 * If no base URL is provided, the current page's pathname is used.
 * @returns The base URL for the object table.
 */
function resolveBaseUrl(): string {
    if (props.baseUrl) {
        return props.baseUrl;
    }

    const url = new URL(window.location.href);
    return url.pathname;
}

/**
 * Navigates to the specified page.
 * @param page The page number to navigate to.
 */
function goToPage(page: number): void {
    if (page < 1 || page === props.page) {
        return;
    }

    const baseUrl = resolveBaseUrl();
    const query: Record<string, unknown> = {
        page,
        perPage: props.perPage,
    };

    if ((inertiaPage.props as Record<string, unknown>).activeTab) {
        query.tab = (inertiaPage.props as Record<string, unknown>).activeTab;
    }

    router.get(baseUrl, query as never, {
        preserveState: true,
        replace: true,
        ...(props.only ? { only: [props.only] } : {}),
    });
}
</script>

<template>
    <div>
        <table>
            <thead>
                <tr>
                    <th v-for="(name, key) in columns" :key="key">
                        {{ name }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in rows" :key="(row.id as number | string)" class="relative">
                    <td v-for="(_name, key) in columns" :key="key">
                        {{ row[key] }}
                    </td>
                    <a :href="row.url" class="absolute inset-0 w-full h-full"></a>
                </tr>
            </tbody>
        </table>
        <div>
            <button type="button" @click="goToPage(page - 1)" :disabled="!hasPrevious">
                Previous
            </button>
            <button type="button" @click="goToPage(page + 1)" :disabled="!hasMore">
                Next
            </button>
        </div>
    </div>
</template>