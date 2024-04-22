<script lang="ts">
import { defineComponent } from 'vue';
import type { UserData } from '@/assets/customTypes';
export default defineComponent({
    name: "LoginView",
    emits: [
        "login"
    ],
    props: {
        "firstName": String,
    },
    created() {
        // Already Logged-In
        // if (this.firstName) this.$router.push("/panel");
    },
    methods: {
        login() {
            const payload: UserData = {
                "firstName": "Stefan",
                "lastName": "Kruik",
                "role": "Administrator",
                "imageUrl": "http://localhost:5173/pfp.png"
            }
            this.$emit("login", payload);
        }
    }
});
</script>

<template>
    <main>
        <p>Login View</p>
        <button type="button" @click="login()">Login Button</button>
    </main>
</template>

<style scoped></style>
