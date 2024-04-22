import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import LoginView from '../views/LoginView.vue';
import PanelView from '../views/PanelView.vue';

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: "active-router-link",
    routes: [
        {
            path: "/",
            name: "home",
            component: HomeView,
            props: true
        },
        {
            path: "/login",
            name: "login",
            component: LoginView,
            props: true
        },
        {
            path: "/panel",
            redirect: "/panel/dashboard"
        },
        {
            path: "/panel/dashboard",
            name: "dashboard",
            component: PanelView,
            props: true
        },
        // TODO: #7
        { path: "/:pathMatch(.*)", component: HomeView, props: true }
    ]
});

export default router;
