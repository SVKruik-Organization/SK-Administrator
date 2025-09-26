<script lang="ts" setup>
import { useThemeStore } from "~/stores/ThemeStore";
const themeStore = useThemeStore();

// Lifecycle Hooks
onMounted(() => themeStore.loadTheme());
</script>

<template>
    <!-- TODO: #20 -->
    <!-- TODO: #25 -->
    <PopupContainer></PopupContainer>
    <NuxtPage></NuxtPage>
</template>
