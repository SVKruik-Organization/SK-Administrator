import { createRouter, createWebHistory } from 'vue-router';

// Views
const HomeView = () => import('../views/HomeView.vue');
const LoginView = () => import('../views/LoginView.vue');
const PanelView = () => import('../views/PanelView.vue');

// Pages
const DashboardPage = () => import('../pages/DashboardPage.vue');
const SettingsPage = () => import('../pages/SettingsPage.vue');
const TemporaryPage = () => import('../pages/TemporaryPage.vue');

// Tabs (sub-pages)
const DashboardNotificationsTab = () => import('../tabs/DashboardNotificationsTab.vue');
const PreferencesSupportTab = () => import('../tabs/PreferencesSupportTab.vue');

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: "active-router-link",
    routes: [
        {
            path: "/",
            component: HomeView,
            props: true
        },
        {
            path: "/login",
            component: LoginView,
            props: true
        },
        // TODO: #14
        {
            path: "/panel", component: PanelView, props: true, children: [
                { path: "", redirect: "/panel/dashboard" },
                {
                    path: "dashboard", component: DashboardPage, props: true, children: [
                        { path: "notifications", component: DashboardNotificationsTab, props: true },
                        { path: ":pathMatch(.*)", redirect: "/panel/dashboard" }
                    ]
                },

                { path: "operations/users", component: TemporaryPage, props: true },
                { path: "operations/teams", component: TemporaryPage, props: true },
                { path: "operations/tickets", component: TemporaryPage, props: true },
                { path: "operations/tasks", component: TemporaryPage, props: true },

                { path: "documents/finance", component: TemporaryPage, props: true },
                { path: "documents/reports", component: TemporaryPage, props: true },
                { path: "documents/staff", component: TemporaryPage, props: true },

                { path: "status/systems", component: TemporaryPage, props: true },
                { path: "status/automation", component: TemporaryPage, props: true },
                { path: "status/logs", component: TemporaryPage, props: true },

                {
                    path: "preferences", component: SettingsPage, props: true, children: [
                        { path: "support", component: PreferencesSupportTab, props: true },
                        { path: ":pathMatch(.*)", redirect: "/panel/preferences" }
                    ]
                },
                { path: ":pathMatch(.*)", redirect: "/panel/dashboard" },
            ]
        },
        // TODO: #7
        { path: "/:pathMatch(.*)", component: HomeView, props: true }
    ]
});

export default router;
