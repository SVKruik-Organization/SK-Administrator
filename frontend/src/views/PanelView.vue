<script lang="ts">
import { defineComponent } from 'vue';
import NotificationContainer from '@/components/NotificationContainer.vue';
import NavbarComponent from '../components/Navbar.vue';
import SideBarComponent from '../components/SideBar.vue';
import { createTicket } from '@/utils/ticket';
import type { PopupItem, PopupPayload } from '@/assets/customTypes';
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
         * @param {PopupPayload} popupPayload The popup data to display.
         */
        popup(popupPayload: PopupPayload) {
            const id: string = `popup${createTicket()}`;
            const childComponent: any = this.$refs["notificationPopup"];
            this.pendingPopups.push({
                "id": id,
                "color": `var(--color-${popupPayload.type})`,
                "message": popupPayload.message
            });

            setTimeout(() => {
                if (childComponent) childComponent.fadeIn(id);
            }, 10);

            setTimeout(() => {
                if (childComponent) childComponent.closePopup(id);
            }, popupPayload.time);
        }
    },
    components: {
        NotificationContainer,
        NavbarComponent,
        SideBarComponent
    },
    data() {
        return {
            popUpForbidden: [""] as Array<string>,
            pendingPopups: [] as Array<PopupItem>
        }
    },
});
</script>

<template>
    <main>
        <NotificationContainer v-if="!popUpForbidden.includes($route.path)" ref="notificationPopup"
            :pendingPopups="pendingPopups"></NotificationContainer>
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
