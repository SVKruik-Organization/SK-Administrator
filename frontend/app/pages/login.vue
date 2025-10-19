<script lang="ts" setup>
const config = useRuntimeConfig();

navigateTo(config.public.authProviderURL + "/login/administrator", {
    external: true
});
</script>