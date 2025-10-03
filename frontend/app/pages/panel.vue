<script lang="ts" setup>
const sideBarStore = useSideBarStore();
definePageMeta({
    middleware: ["auth"],
});

onMounted(() => {
    navigateTo(sideBarStore.firstItemUrl);
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
