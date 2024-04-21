<script lang="ts">
import { defineComponent } from 'vue';
import { RouterLink } from 'vue-router';
export default defineComponent({
    name: "SideBarComponent",
    props: {
        "firstName": String,
        "role": String,
        "imageUrl": String
    }
});
</script>

<template>
    <header>
        <nav>
            <h2 class="logo">Flock</h2>
            <section class="user-information">
                <img :src="imageUrl" alt="Profile Picture">
                <div class="user-information-text">
                    <h3>{{ firstName }}</h3>
                    <p>{{ role }}</p>
                </div>
            </section>
            <RouterLink to="/app/dashboard" class="sidebar-link">
                <i class="fa-regular fa-table-layout"></i>
                <p>Dashboard</p>
            </RouterLink>
            <section class="sidebar-content first-sidebar-content">
                <div class="sidebar-content-item">
                    <h4>Operations</h4>
                    <menu>
                        <RouterLink to="/app/operations/users" class="sidebar-link">
                            <i class="fa-regular fa-user"></i>
                            <p>Users</p>
                        </RouterLink>
                        <RouterLink to="/app/operations/teams" class="sidebar-link">
                            <i class="fa-regular fa-user-group"></i>
                            <p>Teams</p>
                        </RouterLink>
                        <RouterLink to="/app/operations/tickets" class="sidebar-link">
                            <i class="fa-regular fa-bell"></i>
                            <p>Tickets</p>
                        </RouterLink>
                        <RouterLink to="/app/operations/tasks" class="sidebar-link">
                            <i class="fa-regular fa-bars-progress"></i>
                            <p>Tasks</p>
                        </RouterLink>
                    </menu>
                </div>
                <div class="sidebar-content-item">
                    <h4>Documents</h4>
                    <menu>
                        <RouterLink to="/app/documents/finance" class="sidebar-link">
                            <i class="fa-regular fa-credit-card"></i>
                            <p>Finance</p>
                        </RouterLink>
                        <RouterLink to="/app/documents/reports" class="sidebar-link">
                            <i class="fa-regular fa-newspaper"></i>
                            <p>Reports</p>
                        </RouterLink>
                        <RouterLink to="/app/documents/staff" class="sidebar-link">
                            <i class="fa-regular fa-shield-check"></i>
                            <p>Staff</p>
                        </RouterLink>
                    </menu>
                </div>
                <div class="sidebar-content-item">
                    <h4>Status</h4>
                    <menu>
                        <RouterLink to="/app/status/systems" class="sidebar-link">
                            <i class="fa-regular fa-cloud"></i>
                            <p>Systems</p>
                        </RouterLink>
                        <RouterLink to="/app/status/automation" class="sidebar-link">
                            <i class="fa-regular fa-timeline"></i>
                            <p>Automation</p>
                        </RouterLink>
                        <RouterLink to="/app/status/logs" class="sidebar-link">
                            <i class="fa-regular fa-list"></i>
                            <p>Logs</p>
                        </RouterLink>
                    </menu>
                </div>
                <RouterLink to="/app/preferences" class="sidebar-link">
                    <i class="fa-regular fa-gear"></i>
                    <p>Preferences</p>
                </RouterLink>
            </section>
        </nav>
    </header>
</template>

<style scoped>
header {
    width: 250px;
    background-color: var(--color-fill);
    height: 100vh;
    padding: 30px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
}

nav {
    display: flex;
    flex-direction: column;
    gap: 40px;
    flex-grow: 1;
}

/* Header */
.user-information {
    display: flex;
    align-items: center;
    gap: 15px;
}

.user-information-text {
    display: flex;
    flex-direction: column;
    align-items: baseline;
}

img {
    height: 50px;
    aspect-ratio: 1 / 1;
    border-radius: 50%;
    object-fit: cover;
}

/* Content */
.sidebar-content,
.sidebar-content-item {
    display: flex;
    flex-direction: column;
}

.sidebar-content {
    gap: 35px;
    flex-grow: 1;
}

.sidebar-content-item {
    gap: 15px;
}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: 15px;
    height: 40px;
    border-radius: var(--border-radius-high);
    box-sizing: border-box;
    padding: 10px;
    margin-left: -10px;
}

.sidebar-link i {
    width: 15px;
    aspect-ratio: 1 / 1;
}

.first-sidebar-content {
    margin-top: -10px;
}

.sidebar-link:last-child {
    margin-top: auto;
}

/* Active Link */
.active-router-link {
    background-color: var(--color-fill-dark);
}

.active-router-link * {
    font-weight: 600;
}
</style>