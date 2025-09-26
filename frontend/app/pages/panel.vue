<script lang="ts" setup>
definePageMeta({
    middleware: ["auth"],
});
</script>

<template>
    <main>
        <SideBar></SideBar>
        <section class="content-container flex-col">
            <NavBar></NavBar>
            <NuxtPage></NuxtPage>
        </section>
    </main>
</template>

<style scoped>
main {
    display: flex;
}

.content-container {
    flex: 1;
}
</style>
