<script lang="ts" setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

type Breadcrumbs = Array<{
    label: string;
    url: string;
}>;

const breadcrumbs = computed(() =>
    ((page.props.breadcrumbs ?? []) as Breadcrumbs).filter((breadcrumb) => breadcrumb.label !== 'Panel') ?? []);
</script>

<template>
    <div class="flex items-center gap-2" v-if="breadcrumbs.length > 1">
        <div class="flex items-center gap-2 text-sm text-gray-500" v-for="(breadcrumb, index) in breadcrumbs" :key="index">
            <Link :href="breadcrumb.url">
                {{ breadcrumb.label }}
            </Link>
            <span v-if="breadcrumb.url !== breadcrumbs[breadcrumbs.length - 1].url">/</span>
        </div>
    </div>
</template>