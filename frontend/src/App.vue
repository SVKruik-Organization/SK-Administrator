<script lang="ts">
import { defineComponent } from 'vue';
import NavBarComponent from './components/NavBar.vue';
import SideBarComponent from './components/SideBar.vue';

export default defineComponent({
    name: "App",
    components: {
        NavBarComponent,
        SideBarComponent
    }
});
</script>

<template>
    <RouterView></RouterView>
</template>

<style scoped></style>
