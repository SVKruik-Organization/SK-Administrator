<script lang="ts">
import { defineComponent } from 'vue'
import { RouterLink } from 'vue-router';
export default defineComponent({
    name: "HomeView"
});
</script>

<template>
    <main>
        <p>Home Page</p>
        <RouterLink to="/about">To About Page</RouterLink>
    </main>
</template>
