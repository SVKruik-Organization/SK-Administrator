<script lang="ts" setup>
import { computed } from 'vue';
import { router, usePage, Link } from '@inertiajs/vue3';
const inertiaPage = usePage();

// Props
const props = withDefaults(defineProps<{
    rows: Array<Record<string, any>>;
    columns: Record<string, string>;
    page?: number;
    perPage?: number;
    totalRecords?: number;
    totalPages?: number;
    hasMore?: boolean;
    baseUrl?: string;
    only?: string;
    class?: string;
}>(), {
    rows: () => [],
    columns: () => ({}),
    page: 1,
    perPage: 15,
    totalRecords: 0,
    totalPages: 1,
    hasMore: false,
    baseUrl: undefined,
    only: undefined,
    class: '',
});

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

/**
 * Gets the record range for the current page.
 * @returns The record range for the current page.
 */
function getRecordRange(): string {
    const start = (props.page - 1) * props.perPage + 1;
    const end = Math.min(props.page * props.perPage, props.totalRecords);
    return `${start} - ${end} of ${props.totalRecords}`;
}
</script>

<template>
    <div class="flex flex-col">
    <table class="w-full" :class="class">
        <thead class="bg-theme">
            <tr>
                <th v-for="(key) in columns" :key="key" :title="key"
                    class="text-left p-2 first-of-type:rounded-tl-md last-of-type:rounded-tr-md">
                    {{ key }}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="row in rows" :key="(row.id as number | string)" title="Click to view model."
                class="relative hover:bg-theme-dark">
                <td v-for="(_name, key) in columns" :key="key"
                    class="border-b-2 border-l-2 border-theme p-2 text-left last-of-type:border-r-2">
                    {{ row[key] }}
                </td>
                <Link :href="row.url" class="absolute inset-0 w-full h-full"></Link>
            </tr>
        </tbody>
    </table>
    <div class="flex justify-between gap-2 bg-theme rounded-b-md px-4 py-1" v-if="totalRecords > 0">
        <div class="flex gap-2 items-center">
            <span class="text-sm">
                Showing: {{ getRecordRange() }}
            </span>
        </div>
        <div class="flex gap-2 items-center" v-if="totalPages > 1">
            <button type="button" @click="goToPage(page - 1)" :disabled="!hasPrevious"
                :class="{ 'opacity-50 pointer-events-none': !hasPrevious }" title="Previous page"
                class="text-theme-dark0 hover:text-sky-700 hover:bg-sky-200 rounded-md p-1 cursor-pointer">
                <i class="fa-regular fa-angle-left"></i>
            </button>
            <button v-for="i in totalPages" :key="i" type="button" @click="goToPage(i)"
                :class="{ 'bg-sky-200 text-sky-700': i === page }" :title="`Go to page ${i}`"
                class="text-theme-dark0 hover:text-sky-700 hover:bg-sky-200 rounded-md px-2 py-1 cursor-pointer">
                {{ i }}
            </button>
            <button type="button" @click="goToPage(page + 1)" :disabled="!hasMore"
                :class="{ 'opacity-50 pointer-events-none': !hasMore }" title="Next page"
                class="text-theme-dark0 hover:text-sky-700 hover:bg-sky-200 rounded-md p-1 cursor-pointer">
                <i class="fa-regular fa-angle-right"></i>
                </button>
            </div>
        </div>
    </div>
</template>