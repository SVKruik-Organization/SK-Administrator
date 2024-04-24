<script lang="ts">
import { defineComponent } from 'vue';
import type { UserData } from '@/assets/customTypes';
import { useUserStore } from '@/stores/UserStore';

export default defineComponent({
    name: "LoginView",
    setup() {
        return {
            userStore: useUserStore()
        }
    },
    created() {
        // Already Logged-In
        if (this.userStore.user.firstName) this.$router.push("/panel/dashboard");
    },
    methods: {
        /**
         * Fetches data and passes the data if correct upwards.
         */
        login() {
            // TODO #11
            const payload: UserData = {
                "firstName": "Stefan",
                "lastName": "Kruik",
                "role": "Administrator",
                "imageUrl": "http://localhost:5173/pfp.png"
            }
            this.userStore.setUser(payload);
            this.$router.push("/panel");
        }
    }
});
</script>

<template>
    <main>
        <p>Login View</p>
        <button title="Login" type="button" @click="login()">Login Button</button>
    </main>
</template>

<style scoped></style>
