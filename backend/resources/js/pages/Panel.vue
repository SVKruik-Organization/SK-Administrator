<script lang="ts" setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Auth } from '@/types';

const page = usePage();
const auth = computed(() => page.props.auth as Auth);

console.log(auth.value);
</script>

<template>

    <Head title="Panel" />

    <h1>Panel</h1>
    <p>{{ page.props }}</p>
</template>