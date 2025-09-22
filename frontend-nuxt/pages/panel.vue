<script lang="ts" setup>
import PopupContainer from "@/components/PopupContainer.vue";
import NavBar from "~/components/NavBar.vue";
import SideBar from "@/components/SideBar.vue";
import { UserTypes } from "~/assets/customTypes";
const userStore = useUserStore();

definePageMeta({
    middleware: ["auth"],
});

onMounted(() => {
    userStore.setUser({
        "id": 1,
        "firstName": "Stefan",
        "lastName": "Kruik",
        "email": "me@stefankruik.nl",
        "role": UserTypes.ADMIN,
        "imageUrl": "http://localhost:9099/pfp-temp.png"
    });
});
</script>

<template>
    <PopupContainer></PopupContainer>
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
