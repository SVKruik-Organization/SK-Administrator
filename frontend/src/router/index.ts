import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import LoginView from '../views/LoginView.vue';
import PanelView from '../views/PanelView.vue';
import DashboardPage from '@/pages/DashboardPage.vue';

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
            path: "/panel", component: PanelView, props: true, children: [
                { path: "", redirect: "/panel/dashboard" },
                { path: "dashboard", component: DashboardPage, props: true },
                { path: "preferences", component: DashboardPage, props: true },

                { path: "operations/users", component: DashboardPage, props: true },
                { path: "operations/teams", component: DashboardPage, props: true },
                { path: "operations/tickets", component: DashboardPage, props: true },
                { path: "operations/tasks", component: DashboardPage, props: true },

                { path: "documents/finance", component: DashboardPage, props: true },
                { path: "documents/reports", component: DashboardPage, props: true },
                { path: "documents/staff", component: DashboardPage, props: true },

                { path: "status/systems", component: DashboardPage, props: true },
                { path: "status/automation", component: DashboardPage, props: true },
                { path: "status/logs", component: DashboardPage, props: true }
            ]
        },
        // TODO: #7
        { path: "/:pathMatch(.*)", component: HomeView, props: true }
    ]
});

export default router;
