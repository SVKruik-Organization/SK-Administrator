<script lang="ts">
import { defineComponent } from 'vue';
import NotificationContainer from '@/components/NotificationContainer.vue';
import NavBarComponent from '../components/NavBar.vue';
import SideBarComponent from '../components/SideBar.vue';
import { createTicket } from '@/utils/ticket';
import type { PopupItem, PopupPayload } from '@/assets/customTypes';
export default defineComponent({
    name: "PanelView",
    emits: [
        "signOut"
    ],
    components: {
        NotificationContainer,
        NavBarComponent,
        SideBarComponent
    },
    data() {
        return {
            popUpForbidden: [""] as Array<string>,
            pendingPopups: [] as Array<PopupItem>
        }
    },
    props: {
        "firstName": String,
        "lastName": String,
        "role": String,
        "imageUrl": String
    },
    mounted() {
        // TODO: # 8
        //if (!this.firstName) return this.$router.push("/login");
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
    }
});
</script>

<template>
    <main>
        <NotificationContainer ref="notificationPopup" :pendingPopups="pendingPopups"
            v-if="!popUpForbidden.includes($route.path)"></NotificationContainer>
        <SideBarComponent :first-name="firstName" :role="role" :image-url="imageUrl"></SideBarComponent>
        <section class="content-container">
            <NavBarComponent :first-name="firstName" :last-name="lastName" :role="role" :image-url="imageUrl"
                @sign-out="$emit('signOut')">
            </NavBarComponent>
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
    padding: 30px;
    padding-top: 20px;
    height: 100%;
}
</style>
