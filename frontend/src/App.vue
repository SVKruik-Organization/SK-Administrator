<script lang="ts">
import { defineComponent } from 'vue';
import NavBar from './components/NavBar.vue';
export default defineComponent({
    name: "App"
});
</script>

<template>
    <NavBar></NavBar>
    <main>
        <RouterView />
    </main>
</template>

<style scoped></style>
