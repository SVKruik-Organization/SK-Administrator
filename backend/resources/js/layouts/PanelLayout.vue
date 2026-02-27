<script lang="ts" setup>
import SideBar from '@/components/SideBar.vue';
import NavBar from '@/components/NavBar.vue';
</script>

<template>
    <main class="flex">
        <SideBar></SideBar>
        <section class="flex flex-col">
            <NavBar></NavBar>
            <slot />
        </section>
    </main>
</template>