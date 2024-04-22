<script lang="ts">
import { defineComponent } from 'vue';
import { RouterView } from 'vue-router';
import NavBarComponent from './components/NavBar.vue';
import SideBarComponent from './components/SideBar.vue';
import type { UserData } from './assets/customTypes';
export default defineComponent({
    name: "App",
    components: {
        NavBarComponent,
        SideBarComponent
    },
    data() {
        return {
            "userData": {
                "firstName": null as unknown,
                "lastName": null as unknown,
                "role": null as unknown,
                "imageUrl": null as unknown
            } as UserData
        }
    },
    methods: {
        signOn(userData: UserData) {
            console.log(userData);
            this.userData = userData;
            this.$router.push("/panel");
        },
        signOut() {
            // TODO: #8
            this.$router.push("/");
        }
    }
});
</script>

<template>
    <RouterView :first-name="userData.firstName" :last-name="userData.lastName" :role="userData.role"
        :image-url="userData.imageUrl" @sign-out="signOut()" @login="signOn">
    </RouterView>
</template>

<style scoped></style>
