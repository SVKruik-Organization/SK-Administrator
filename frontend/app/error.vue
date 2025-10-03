<script lang="ts" setup>
const error = useError();

const handleRedirect = () => clearError({ redirect: "/" });
</script>
<template>
    <div>
        <h1>{{ error?.statusCode || 404 }}</h1>
        <p>Something went wrong on our end! Please try again later.</p>
        <button @click="handleRedirect">Go Back Home</button>
    </div>
</template>