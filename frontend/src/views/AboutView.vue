<script lang="ts">
import { defineComponent } from 'vue'
import { RouterLink } from 'vue-router';
export default defineComponent({
    name: "AboutView"
});
</script>

<template>
    <main>
        <p>About Page</p>
        <RouterLink to="/">To Home Page</RouterLink>
    </main>
</template>
