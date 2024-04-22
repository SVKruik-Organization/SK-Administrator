<script lang="ts">
import { defineComponent } from 'vue';
import { RouterLink } from 'vue-router';
export default defineComponent({
    name: "HomeView",
});
</script>

<template>
    <main>
        <p>Home View</p>
        <RouterLink to="/login">Login</RouterLink>
    </main>
</template>

<style scoped></style>
