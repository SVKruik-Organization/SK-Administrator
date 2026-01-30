<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

</script>

<template>

    <Head title="Landing" />
    <div class="flex flex-col">
        <h1>Welcome to SK Administrator</h1>
        <Link href="/panel">Go to Dashboard</Link>
        <a href="/authentication/login">Login</a>
    </div>
</template>