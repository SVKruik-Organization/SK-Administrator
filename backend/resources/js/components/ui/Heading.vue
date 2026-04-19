<script lang="ts" setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Button from '@/components/ui/form/Button.vue';

const page = usePage();

const props = defineProps<{
    cta?: {
        label: string;
        url: string;
    };
}>();

const breadcrumbs = computed(() => page.props.breadcrumbs.items);
const title = computed(() => page.props.breadcrumbs.title);

</script>

<template>
    <div v-if="title.length || breadcrumbs.length" class="flex flex-col gap-2">
        <div class="flex items-center gap-2" v-if="breadcrumbs.length > 1">
            <div class="flex items-center gap-2 text-sm text-gray-500" v-for="(breadcrumb, index) in breadcrumbs"
                :key="index">
                <Link :href="breadcrumb.url">
                    {{ breadcrumb.label }}
                </Link>
                <span v-if="breadcrumb.url !== breadcrumbs[breadcrumbs.length - 1].url">/</span>
            </div>
        </div>
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-medium text-gray-700">{{ title }}</h3>
            <Button v-if="props.cta" :label="props.cta.label" :href="props.cta.url" icon="fa-regular fa-plus" />
        </div>
    </div>
</template>