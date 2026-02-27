<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { index as panelIndex } from '@/routes/panel';
import { login as loginIndex } from '@/routes/authentication';
</script>

<template>

    <Head title="Landing" />

    <div class="flex flex-col">
        <h1>Welcome to SK Administrator</h1>
        <Link :href="panelIndex.url()">Go to Dashboard</Link>
        <a :href="loginIndex.url()">Login</a>
    </div>
</template>