<script lang="ts">
import { defineComponent } from 'vue';
import PopupContainer from '@/components/PopupContainer.vue';
import NavbarComponent from '../components/Navbar.vue';
import SideBarComponent from '../components/SideBar.vue';
import type { PopupItem } from '@/assets/customTypes';
import { useUserStore } from '@/stores/UserStore';

export default defineComponent({
    name: "PanelView",
    setup() {
        return {
            userStore: useUserStore()
        }
    },
    created() {
        // Not Logged-In
        if (!this.userStore.user.firstName) return this.$router.push("/login");
    },
    methods: {
        /**
         * Show a popup to the user.
         * @param {PopupItem} popupPayload The popup data to display.
         */
        popup(popupPayload: PopupItem) {
            this.pendingPopups.push(popupPayload);
        }
    },
    components: {
        PopupContainer,
        NavbarComponent,
        SideBarComponent
    },
    data() {
        return {
            "pendingPopups": [] as Array<PopupItem>
        }
    },
});
</script>

<template>
    <main>
        <PopupContainer ref="notificationPopup" :pendingPopups="pendingPopups"></PopupContainer>
        <SideBarComponent></SideBarComponent>
        <section class="content-container">
            <NavbarComponent></NavbarComponent>
            <RouterView class="content-view" @popup="popup"></RouterView>
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
    padding: 20px 30px 30px;
    height: 100%;
}
</style>
