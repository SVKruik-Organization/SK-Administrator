<script lang="ts" setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Auth } from '@/types';
import PanelLayout from '@/layouts/PanelLayout.vue';

defineOptions({
    layout: PanelLayout,
});

const page = usePage();
const auth = computed(() => page.props.auth as Auth);
</script>

<template>
    <Head title="Panel" />

    <h1>Panel</h1>
    <p>Active user ID: {{ auth.user.id }}</p>
    <p>Active profile: {{ auth.active_profile.name }}</p>
</template>