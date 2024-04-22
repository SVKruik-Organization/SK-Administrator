<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    name: "HomeView"
});
</script>

<template>
    <main>
        <p>Home View</p>
        <RouterLink to="/login">Login</RouterLink>
    </main>
</template>

<style scoped></style>
