<script lang="ts" setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const breadcrumbs = computed(() => page.props.breadcrumbs.items);
</script>

<template>
    <div class="flex flex-col gap-2">
        <div class="flex items-center gap-2" v-if="breadcrumbs.length > 1">
            <div class="flex items-center gap-2 text-sm text-gray-500" v-for="(breadcrumb, index) in breadcrumbs"
                :key="index">
                <Link :href="breadcrumb.url">
                    {{ breadcrumb.label }}
                </Link>
                <span v-if="breadcrumb.url !== breadcrumbs[breadcrumbs.length - 1].url">/</span>
            </div>
        </div>
        <h3 class="text-2xl font-medium text-gray-700">{{ page.props.breadcrumbs.title }}</h3>
    </div>
</template>