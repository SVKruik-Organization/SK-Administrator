<script lang="ts">
import { defineComponent } from 'vue';
import NavBarComponent from '../components/NavBar.vue';
import SideBarComponent from '../components/SideBar.vue';
export default defineComponent({
    name: "PanelView",
    emits: [
        "signOut"
    ],
    components: {
        NavBarComponent,
        SideBarComponent
    },
    props: {
        "firstName": String,
        "lastName": String,
        "role": String,
        "imageUrl": String
    },
    mounted() {
        if (!this.firstName) return this.$router.push("/login");
    }
});
</script>

<template>
    <main>
        <SideBarComponent :first-name="firstName" :role="role" :image-url="imageUrl"></SideBarComponent>
        <section class="content-container">
            <NavBarComponent :first-name="firstName" :last-name="lastName" :role="role" :image-url="imageUrl"
                @sign-out="$emit('signOut')">
            </NavBarComponent>
            <RouterView class="content-view"></RouterView>
        </section>
    </main>
</template>

<style scoped>
main {
    display: flex;
}

.content-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 100%;
    flex: 1;
}

.content-view {
    box-sizing: border-box;
    padding: 30px;
}
</style>
