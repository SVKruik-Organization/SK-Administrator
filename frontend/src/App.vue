<script lang="ts">
import { defineComponent } from 'vue';
import { useThemeStore } from './stores/ThemeStore';

export default defineComponent({
    name: "App",
    setup() {
        return {
            themeStore: useThemeStore()
        }
    },
    mounted() {
        this.themeStore.loadTheme();
    }
});
</script>

<template>
    <!-- TODO: #20 -->
    <!-- TODO: #25 -->
    <RouterView></RouterView>
</template>

<style scoped></style>
