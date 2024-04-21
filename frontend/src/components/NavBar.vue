<script lang="ts">
import { defineComponent } from 'vue';
export default defineComponent({
    name: "NavbarComponent",
    props: {
        "firstName": String,
        "lastName": String,
        "imageUrl": String
    },
    methods: {
        /**
         * Open or close the dropdown.
         */
        toggleDropdown(): void {
            const caret: HTMLElement = document.getElementsByClassName("dropdown-icon")[0] as HTMLElement;
            const menu: HTMLMenuElement = document.getElementsByClassName("dropdown-menu")[0] as HTMLMenuElement;
            if (caret.classList.contains("rotated")) {
                this.closeDropdown();
            } else {
                if (caret) caret.classList.add("rotated");
                if (menu) menu.classList.add("visible");
            }
        },
        /**
         * Close the dropdown, regardless of current state.
         */
        closeDropdown(): void {
            const caret: HTMLElement = document.getElementsByClassName("dropdown-icon")[0] as HTMLElement;
            const menu: HTMLMenuElement = document.getElementsByClassName("dropdown-menu")[0] as HTMLMenuElement;
            if (caret) caret.classList.remove("rotated");
            if (menu) menu.classList.remove("visible");
        },
        signOut(): void {
            // TODO: #8
            this.$router.push("/");
        }
    },
    mounted() {
        // Global Close Dropdown
        document.body.addEventListener("click", (event: MouseEvent): void => {
            const eventTarget: HTMLElement = event.target as HTMLElement;
            if (!eventTarget) return;
            if (!eventTarget.classList.contains("click-item")) this.closeDropdown();
        });
    },
});
</script>

<template>
    <nav>
        <div class="searchbar-container navbar-pill">
            <!-- TODO: #9 -->
            <i class="fa-regular fa-eye"></i>
            <!-- TODO: #5 <i class="fa-regular fa-magnifying-glass"></i> -->
            <input type="text" placeholder="Search">
        </div>
        <section class="nav-right">
            <div class="notification-icon navbar-pill">
                <i class="fa-regular fa-bell"></i>
                <!-- TODO: #2 <i class="fa-regular fa-bell-ring"></i> -->
            </div>
            <div class="user-pill-container">
                <div class="navbar-pill user-pill">
                    <img :src="imageUrl" alt="Profile Picture">
                    <p>{{ firstName }} {{ lastName }}</p>
                    <i class="fa-regular fa-square-caret-down dropdown-icon"></i>
                    <!-- TODO: #5 <i class="fa-regular fa-angle-down"></i> -->
                    <span class="click-item" @click="toggleDropdown()"></span>
                </div>
                <div class="dropdown-menu shadow">
                    <RouterLink to="/" class="dropdown-item">Home</RouterLink>
                    <RouterLink to="/preferences/support" class="dropdown-item">Support</RouterLink>
                    <span class="splitter"></span>
                    <button type="button" class="sign-out-button dropdown-item" @click="signOut()">
                        <p>Sign Out</p>
                        <i class="fa-regular fa-arrow-right-from-bracket"></i>
                    </button>
                </div>
            </div>
        </section>
    </nav>
</template>

<style scoped>
nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-sizing: border-box;
    padding: 25px;
    height: fit-content;
    border-bottom: 2px solid var(--color-border);
}

.navbar-pill {
    display: flex;
    align-items: center;
    box-sizing: border-box;
    padding: 10px 15px;
    background-color: var(--color-fill);
    height: 40px;
    cursor: pointer;
}

.navbar-pill * {
    color: var(--color-text-light);
    user-select: none;
}

.navbar-pill i {
    width: 15px;
    aspect-ratio: 1 / 1;
}

.navbar-pill:hover {
    background-color: var(--color-fill-dark);
}

/* Left */
.searchbar-container {
    gap: 15px;
    border-radius: var(--border-radius-high);
    width: 400px;
}

/* Right */
.nav-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

.notification-icon {
    justify-content: center;
    border-radius: 50%;
    aspect-ratio: 1 / 1;
}

.user-pill {
    display: flex;
    align-items: center;
    gap: 10px;
    width: fit-content;
    min-width: 200px;
    border-radius: var(--border-radius-high);
    position: relative;
}

.user-pill p {
    min-width: 150px;
}

img {
    height: 20px;
    aspect-ratio: 1 / 1;
    border-radius: 50%;
    object-fit: cover;
}

/* Dropdown */
.click-item {
    width: 100%;
    height: 100%;
    background-color: transparent;
    margin-left: -15px;
    position: absolute;
    z-index: 1;
}

.dropdown-menu {
    display: none;
    flex-direction: column;
    gap: 5px;
    position: absolute;
    width: 150px;
    right: 25px;
    height: fit-content;
    top: 80px;
    background-color: var(--color-fill);
    border-radius: calc(var(--border-radius-low) + 5px);
    user-select: none;
    z-index: 1;
    box-sizing: border-box;
    padding: 5px;
}

.rotated {
    rotate: 180deg;
}

.visible {
    display: flex;
}

.dropdown-item {
    box-sizing: border-box;
    padding: 5px;
    border-radius: var(--border-radius-low);
}

.dropdown-item:hover {
    background-color: var(--color-fill-dark);
}

.sign-out-button {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.sign-out-button i {
    color: var(--color-danger);
}
</style>