<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
    name: "NavbarComponent"
});
</script>

<template>
    <header>
        <nav>
            <h2 class="logo">Flock</h2>
            <section class="user-information">
                <img src="/pfp.png" alt="Profile Picture">
                <div class="user-information-text">
                    <h3>Stefan</h3>
                    <p>Administrator</p>
                </div>
            </section>
            <div class="navbar-link">
                <i class="fa-solid fa-star"></i>
                <p>Home</p>
            </div>
            <section class="navbar-content">
                <div class="navbar-content-item">
                    <h4>Operations</h4>
                    <ul>
                        <li class="navbar-link">
                            <i class="fa-solid fa-star"></i>
                            <p>Link A</p>
                        </li>
                        <li class="navbar-link">
                            <i class="fa-solid fa-star"></i>
                            <p>Link B</p>
                        </li>
                        <li class="navbar-link">
                            <i class="fa-solid fa-star"></i>
                            <p>Link C</p>
                        </li>
                        <li class="navbar-link">
                            <i class="fa-solid fa-star"></i>
                            <p>Link D</p>
                        </li>
                        <li class="navbar-link">
                            <i class="fa-solid fa-star"></i>
                            <p>Link E</p>
                        </li>
                    </ul>
                </div>
                <div class="navbar-content-item">
                    <h4>Media</h4>
                    <ul>
                        <li class="navbar-link">
                            <i class="fa-solid fa-star"></i>
                            <p>Link A</p>
                        </li>
                        <li class="navbar-link">
                            <i class="fa-solid fa-star"></i>
                            <p>Link B</p>
                        </li>
                        <li class="navbar-link">
                            <i class="fa-solid fa-star"></i>
                            <p>Link C</p>
                        </li>
                        <li class="navbar-link">
                            <i class="fa-solid fa-star"></i>
                            <p>Link D</p>
                        </li>
                    </ul>
                </div>
                <div class="navbar-content-item">
                    <h4>Externals</h4>
                    <ul>
                        <li class="navbar-link">
                            <i class="fa-solid fa-star"></i>
                            <p>Link A</p>
                        </li>
                        <li class="navbar-link">
                            <i class="fa-solid fa-star"></i>
                            <p>Link B</p>
                        </li>
                        <li class="navbar-link">
                            <i class="fa-solid fa-star"></i>
                            <p>Link C</p>
                        </li>
                    </ul>
                </div>
                <div class="navbar-content-item">
                    <h4>Extra's</h4>
                    <ul>
                        <li class="navbar-link">
                            <i class="fa-solid fa-star"></i>
                            <p>Link A</p>
                        </li>
                        <li class="navbar-link">
                            <i class="fa-solid fa-star"></i>
                            <p>Link B</p>
                        </li>
                    </ul>
                </div>
                <div class="navbar-link">
                    <i class="fa-solid fa-star"></i>
                    <p>Settings</p>
                </div>
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
.navbar-content,
.navbar-content-item {
    display: flex;
    flex-direction: column;
}

.navbar-content {
    gap: 35px;
    flex-grow: 1;
}

.navbar-content-item {
    gap: 20px;
}

ul {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.navbar-link {
    display: flex;
    align-items: center;
    gap: 10px;
}

div.navbar-link:first-of-type {
    margin-bottom: -10px;
}

.navbar-link:last-child {
    margin-top: auto;
}
</style>