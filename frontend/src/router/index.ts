import { createRouter, createWebHistory } from 'vue-router';

// Views
const HomeView = () => import('../views/HomeView.vue');
const LoginView = () => import('../views/LoginView.vue');
const PanelView = () => import('../views/PanelView.vue');

// Temporary Items
const TemporaryPage = () => import('../pages/TemporaryPage.vue');
const TemporaryTab = () => import('../tabs/TemporaryTab.vue');

// Pages
const DashboardPage = () => import('../pages/DashboardPage.vue');
const PreferencesPage = () => import('../pages/PreferencesPage.vue');
const TeamsPage = () => import('../pages/TeamsPage.vue');
const TicketsPage = () => import('../pages/TicketsPage.vue');
const AccountsPage = () => import('../pages/AccountsPage.vue');

// Tabs (sub-pages)
const DashboardNotificationsTab = () => import('../tabs/DashboardNotificationsTab.vue');
const PreferencesSupportTab = () => import('../tabs/PreferencesSupportTab.vue');
const TeamsFeaturesTab = () => import('../tabs/TeamsFeaturesTab.vue');
const TeamsOverviewTab = () => import('../tabs/TeamsOverviewTab.vue');
const AccountsMembersTab = () => import('../tabs/AccountsMembersTab.vue');
const AccountsOwnersTab = () => import('../tabs/AccountsOwnersTab.vue');
const AccountsUsersTab = () => import('../tabs/AccountsUsersTab.vue');

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: "active-router-link",
    linkExactActiveClass: "active-exact-router-link",
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
        {
            path: "/panel", component: PanelView, props: true, children: [
                { path: "", redirect: "/panel/dashboard" },
                {
                    path: "dashboard", component: DashboardPage, props: true, children: [
                        { path: "", redirect: "/panel/dashboard/notifications" },
                        { path: "notifications", component: DashboardNotificationsTab, props: true },
                        { path: ":pathMatch(.*)", redirect: "/panel/dashboard/notifications" }
                    ]
                },
                {
                    path: "operations/accounts", component: AccountsPage, props: true, children: [
                        { path: "", redirect: "/panel/operations/accounts/members" },
                        { path: "members", component: AccountsMembersTab, props: true },
                        { path: "owners", component: AccountsOwnersTab, props: true },
                        { path: "users", component: AccountsUsersTab, props: true },
                        { path: ":pathMatch(.*)", redirect: "/panel/operations/accounts/members" }
                    ]
                },
                {
                    path: "operations/teams", component: TeamsPage, props: true, children: [
                        { path: "", redirect: "/panel/operations/teams/overview" },
                        { path: "overview", component: TeamsOverviewTab, props: true },
                        { path: "features", component: TeamsFeaturesTab, props: true },
                        { path: ":pathMatch(.*)", redirect: "/panel/operations/teams/overview" }
                    ]
                },
                {
                    path: "operations/tickets", component: TicketsPage, props: true, children: [
                        { path: "", redirect: "/panel/operations/tickets/pending" },
                        { path: "pending", component: TemporaryTab, props: true },
                        { path: "active", component: TemporaryTab, props: true },
                        { path: "closed", component: TemporaryTab, props: true },
                        { path: ":pathMatch(.*)", redirect: "/panel/operations/tickets/pending" }
                    ]
                },
                { path: "operations/tasks", component: TemporaryPage, props: true },

                { path: "documents/finance", component: TemporaryPage, props: true },
                { path: "documents/reports", component: TemporaryPage, props: true },
                { path: "documents/staff", component: TemporaryPage, props: true },

                { path: "status/systems", component: TemporaryPage, props: true },
                { path: "status/automation", component: TemporaryPage, props: true },
                { path: "status/logs", component: TemporaryPage, props: true },

                {
                    path: "preferences", component: PreferencesPage, props: true, children: [
                        { path: "", redirect: "/panel/preferences/support" },
                        { path: "support", component: PreferencesSupportTab, props: true },
                        { path: ":pathMatch(.*)", redirect: "/panel/preferences/support" }
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
