<script lang="ts">
import { defineComponent } from 'vue';
import NavbarComponent from './components/NavBar.vue';
export default defineComponent({
    name: "App",
    components: {
        NavbarComponent
    }
});
</script>

<template>
    <main>
        <NavbarComponent></NavbarComponent>
        <RouterView />
    </main>
</template>

<style scoped>
main {
    display: flex;
    gap: 10px;
}
</style>
