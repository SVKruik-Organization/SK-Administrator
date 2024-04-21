import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import DashboardPage from '../pages/DashboardPage.vue';

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: "active-router-link",
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView
        },
        {
            path: '/app/dashboard',
            name: 'dashboard',
            component: DashboardPage
        },
        // TODO: #7
        { path: "/:pathMatch(.*)", component: HomeView }
    ]
});

export default router;
